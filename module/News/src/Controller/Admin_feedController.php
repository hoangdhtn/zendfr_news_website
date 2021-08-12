<?php

declare(strict_types=1);

namespace News\Controller;

use Zend\Mvc\MvcEvent;
use Laminas\Authentication\AuthenticationService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Feed\Reader as Feed;
use Laminas\Http\Request;
use News\Model\Table\DanhmucfeedTable;

use News\Form\Feed\AddFeedForm;
use News\Form\Feed\EditFeedForm;
use News\Form\Feed\DelFeedForm;

class Admin_feedController extends AbstractActionController
{
	private $DanhmucfeedTable;

	public function __construct(DanhmucfeedTable $danhmucfeedTable)
	{
		$this->danhmucfeedTable = $danhmucfeedTable;
	}

	public function onDispatch(MvcEvent $e)
	{
		$reponse = parent::onDispatch($e);
		$this->layout()->setTemplate('layout/dashboard');
		return $reponse;
	}

	public function indexAction()
	{
		$paginator = $this->danhmucfeedTable->fetchAllDanhMucFeed(true);
        $page = (int) $this->params()->fromQuery('page', 1); 
        $page = ($page < 1) ? 1 : $page;
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(5);
        
        return new ViewModel(
            ['datas' => $paginator]
        );
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

		$addfeedForm = new AddFeedForm();
		$request = $this->getRequest();

		if ($this->getRequest()->isPost()) {

			$formData = $request->getPost()->toArray();
			$addfeedForm->setInputFilter($this->danhmucfeedTable->getAddFeedFilter());
			$addfeedForm->setData($formData);

			if ($addfeedForm->isValid()) {
				try {
					$datas = $addfeedForm->getData();

					$this->danhmucfeedTable->addFeed($datas);

					$this->flashMessenger()->addSuccessMessage('Thêm danh mục thành công');
					return $this->redirect()->toRoute('admin_feed', ['action' => 'index']);
				} catch (\RuntimeException $exception) {
					$this->flashMessenger()->addErrorMessage($exception->getMessage());
					return $this->redirect()->refresh();
				}
			}
		}

		return new ViewModel([
			'form' => $addfeedForm,
		]);


	}

	public function editAction()
	{
		$id = (int) $this->params()->fromRoute('id');
		# make sure whoever accesses this page is logged in
		$auth = new AuthenticationService();
		if (!$auth->hasIdentity()) {
			return $this->redirect()->toRoute('login');
		}

		# make sure that only the admin can acess this page
		if (!$this->authPlugin()->getRoleId() == 1) {
			return $this->notFoundAction();
		}

		$editfeedForm = new EditFeedForm();
		$request = $this->getRequest();

		//Lấy dữ liệu từ model
		$data = $this->danhmucfeedTable->fetchLinkDanhMucFeed($id);



		if ($this->getRequest()->isPost()) {

			$formData = $request->getPost()->toArray();
			$editfeedForm->setInputFilter($this->danhmucfeedTable->getEditFeedFilter());
			$editfeedForm->setData($formData);

			if ($editfeedForm->isValid()) {
				try {
					$feedId = (int) $this->params()->fromRoute('id');
					$datas = $editfeedForm->getData();
							
					$this->danhmucfeedTable->updateFeed($datas, $feedId);

					$this->flashMessenger()->addSuccessMessage('Danh mục cập nhật thành công');
					return $this->redirect()->toRoute('admin_feed', ['action' => 'edit', 'id' => $feedId]);
				} catch (\RuntimeException $exception) {
					$this->flashMessenger()->addErrorMessage($exception->getMessage());
					return $this->redirect()->refresh();
				}
			}
		} else {
			$datas = [
				'ten' => $data->getTenDanhMuc(),
				'link' => $data->getLink(),

			];
			$editfeedForm->setData($datas);
			//$id = $newsId;
			return new ViewModel([
				'form' => $editfeedForm,
				'id' => $id,
			]);
		}
	}

	public function deleteAction()
	{
		// $delcateForm = new DeleteCateForm();

		// return new ViewModel([
		// 	'form' => $delcateForm

		// ]);


		$feedId = (int) $this->params()->fromRoute('id');
		if (!is_numeric($feedId) || !$this->danhmucfeedTable->fetchLinkDanhMucFeed($feedId)) {
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

		$delfeedForm = new DelFeedForm();
		$request = $this->getRequest();

		if ($request->isPost()) {
			$formData = $request->getPost()->toArray();
			$delfeedForm->setData($formData);

			if ($delfeedForm->isValid()) {
				if ($request->getPost()->get('del_feed') == 'Yes') {
					$this->danhmucfeedTable->deleteFeed((int) $feedId);
					$this->flashMessenger()->addSuccessMessage('Xóa danh mục thành công.');

					return $this->redirect()->toRoute('admin_feed', ['action' => 'index']);
				}

				# otherwise return to the homepage
				return $this->redirect()->toRoute('admin_feed', ['action' => 'index']);
			}
		}

		// return new ViewModel(['id' => $userId, 'form' => $deleteForm]);
		return new ViewModel(['form' => $delfeedForm, 'id' => $feedId]);
	}


}
