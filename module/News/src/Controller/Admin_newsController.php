<?php

declare(strict_types=1);

namespace News\Controller;

use Zend\Mvc\MvcEvent;
use Laminas\Authentication\AuthenticationService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\File\Transfer;

use News\Model\Table\TintucTable;
use News\Model\Table\TheloaiTable;

use News\Form\Admin\AddnewsForm;
use News\Form\Admin\EditnewsForm;
use News\Form\Admin\DeletenewsForm;

class Admin_newsController extends AbstractActionController
{
	private $tintucTable;
	private $theloaiTable;

	public function __construct(TintucTable $tintucTable, TheloaiTable $theloaiTable)
	{
		$this->tintucTable = $tintucTable;
		$this->theloaiTable = $theloaiTable;
	}

	public function onDispatch(MvcEvent $e)
	{
		$reponse = parent::onDispatch($e);
		$this->layout()->setTemplate('layout/dashboard');
		return $reponse;
	}

	public function indexAction()
	{
		# make sure whoever accesses this page is logged in
		$auth = new AuthenticationService();
		if (!$auth->hasIdentity()) {
			return $this->redirect()->toRoute('login');
		}

		# make sure that only the admin can acess this page
		if (!$this->authPlugin()->getRoleId() == 1) {
			return $this->notFoundAction();
		}

		#grab paginator from UsersTable
		$paginator = $this->tintucTable->fetchAllTintuc(true);
		$page = (int) $this->params()->fromQuery('page', 1); # sorry i forgot this line..
		$page = ($page < 1) ? 1 : $page;
		$paginator->setCurrentPageNumber($page);
		$paginator->setItemCountPerPage(5);

		return new ViewModel(['news' => $paginator]);
	}
	public function addAction()
	{
		# make sure whoever accesses this page is logged in
		$auth = new AuthenticationService();
		if (!$auth->hasIdentity()) {
			return $this->redirect()->toRoute('login');
		}

		# make sure that only the admin can acess this page
		if (!$this->authPlugin()->getRoleId() == 1) {
			return $this->notFoundAction();
		}


		// FORM
		$theloai =  $this->theloaiTable->fetchAllTheloai();
		$channel = array();
		foreach ($theloai as $item) {
			$channel[$item->getId()] = $item->getTenTheLoai();
		}
		// echo '<pre>';
		// print_r($channel);
		// echo '</pre>';


		$addnewsForm = new AddnewsForm($channel, 'add_news');
		$request = $this->getRequest();

		if ($this->getRequest()->isPost()) {

			
			//ENDTEST UP FILE

			$formData = $request->getPost()->toArray();
			$addnewsForm->setInputFilter($this->tintucTable->getAddNewsFilter());
			$addnewsForm->setData($formData);

			if ($addnewsForm->isValid()) {
				try {
					$datas = $addnewsForm->getData();
					//print_r($datas);

					//TEST UP FILE
					$img = $this->getRequest()->getPost();
					$upload = new \Laminas\File\Transfer\Adapter\Http();
					$fileInfo = $upload->getFileInfo();
					$fileSize = $upload->getFileSize();
					$fileName = $upload->getFileName('file');

					$upload->setDestination(DOOR . DS . '/tintuc/upload');
					$upload->receive();

					$this->tintucTable->addNews($datas, $fileInfo['file']['name']);

					$this->flashMessenger()->addSuccessMessage('Thêm bài viết thành công');
					return $this->redirect()->toRoute('admin_news', ['action' => 'index']);
				} catch (\RuntimeException $exception) {
					$this->flashMessenger()->addErrorMessage($exception->getMessage());
					return $this->redirect()->refresh();
				}
			}
		}

		return new ViewModel([
			'form' => $addnewsForm,
		]);
	}
	public function editAction()
	{
		$newsId = (int) $this->params()->fromRoute('id');
		# make sure whoever accesses this page is logged in
		$auth = new AuthenticationService();
		if (!$auth->hasIdentity()) {
			return $this->redirect()->toRoute('login');
		}

		# make sure that only the admin can acess this page
		if (!$this->authPlugin()->getRoleId() == 1) {
			return $this->notFoundAction();
		}


		// FORM
		$theloai =  $this->theloaiTable->fetchAllTheloai();
		$channel = array();
		foreach ($theloai as $item) {
			$channel[$item->getId()] = $item->getTenTheLoai();
		}
		// echo '<pre>';
		// print_r($channel);
		// echo '</pre>';


		$editnewsForm = new EditnewsForm($channel, 'edit_news');
		$request = $this->getRequest();

		//Lấy dữ liệu từ model
		$data = $this->tintucTable->fetchNewsById($newsId);



		if ($this->getRequest()->isPost()) {

			

			//ENDTEST UP FILE

			$formData = $request->getPost()->toArray();
			$editnewsForm->setInputFilter($this->tintucTable->getEditNewsFilter());
			$editnewsForm->setData($formData);

			// in ra mảng post
			// echo '<pre>';
			// print_r($fileInfo['file']['name']);
			// echo '</pre>';

			// echo '<pre>';
			// print_r($fileSize);
			// echo '</pre>';

			// echo '<pre>';
			// print_r($fileName);
			// echo '</pre>';


			if ($editnewsForm->isValid()) {
				try {
					$newsId = (int) $this->params()->fromRoute('id');
					$datas = $editnewsForm->getData();
					//TEST UP FILE
					$img = $this->getRequest()->getPost();
					$upload = new \Laminas\File\Transfer\Adapter\Http();
					$fileInfo = $upload->getFileInfo();
					$fileSize = $upload->getFileSize();
					$fileName = $upload->getFileName('file');

					$upload->setDestination(DOOR . DS . '/tintuc/upload');
					$upload->receive();
							
					$this->tintucTable->updateNews($datas, $fileInfo['file']['name'], $newsId);

					$this->flashMessenger()->addSuccessMessage('Bài viết cập nhật thành công');
					return $this->redirect()->toRoute('admin_news', ['action' => 'edit', 'id' => $newsId]);
				} catch (\RuntimeException $exception) {
					$this->flashMessenger()->addErrorMessage($exception->getMessage());
					return $this->redirect()->refresh();
				}
			}
		} else {
			$data = [
				'tieude_news' => $data->getTieuDe(),
				'tomtat_news' => $data->getTomTat(),
				'noidung_news' => $data->getNoiDung(),
				'theloai'		=> $data->getIdTheLoai(),

			];
			$editnewsForm->setData($data);
			$id = $newsId;
			return new ViewModel([
				'form' => $editnewsForm,
				'id' => $id,
			]);
		}
	}
	public function deleteAction()
	{
		$newsId = (int) $this->params()->fromRoute('id');
		if (!is_numeric($newsId) || !$this->tintucTable->fetchNewsById($newsId)) {
			return $this->notFoundAction();
		}

		$auth = new AuthenticationService();
		if (!$auth->hasIdentity()) {
			return $this->redirect()->toRoute('login');
		}

		# make sure that only the admin can acess this page
		if (!$this->authPlugin()->getRoleId() == 1) {
			return $this->notFoundAction();
		}

		$deletenewsForm = new DeletenewsForm();
		$request = $this->getRequest();

		if ($request->isPost()) {
			$formData = $request->getPost()->toArray();
			$deletenewsForm->setData($formData);

			if ($deletenewsForm->isValid()) {
				if ($request->getPost()->get('delete_news') == 'Yes') {
					$this->tintucTable->deleteNews((int) $newsId);
					$this->flashMessenger()->addSuccessMessage('Xóa bài viết thành công.');

					return $this->redirect()->toRoute('admin_news', ['action' => 'index']);
				}

				# otherwise return to the homepage
				return $this->redirect()->toRoute('admin_news', ['action' => 'index']);
			}
		}

		// return new ViewModel(['id' => $userId, 'form' => $deleteForm]);
		return new ViewModel(['form' => $deletenewsForm, 'id' => $newsId]);
	}
}
