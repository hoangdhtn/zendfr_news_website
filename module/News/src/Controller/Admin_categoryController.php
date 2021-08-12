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

use News\Form\Category\EditCategoryForm;
use News\Form\Category\DeleteCateForm;
use News\Form\Category\AddCategoryForm;

class Admin_categoryController extends AbstractActionController
{
	private $theloaiTable;

	public function __construct(TheloaiTable $theloaiTable)
	{

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
		$paginator = $this->theloaiTable->fetchAllTheloaiPa(true);
		$page = (int) $this->params()->fromQuery('page', 1); # sorry i forgot this line..
		$page = ($page < 1) ? 1 : $page;
		$paginator->setCurrentPageNumber($page);
		$paginator->setItemCountPerPage(5);

		return new ViewModel(['theloais' => $paginator]);
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

		$addcateForm = new AddCategoryForm();
		$request = $this->getRequest();

		if ($this->getRequest()->isPost()) {

			$formData = $request->getPost()->toArray();
			$addcateForm->setInputFilter($this->theloaiTable->getAddCateFilter());
			$addcateForm->setData($formData);

			if ($addcateForm->isValid()) {
				try {
					$datas = $addcateForm->getData();

					$this->theloaiTable->addCate($datas);

					$this->flashMessenger()->addSuccessMessage('Thêm danh mục thành công');
					return $this->redirect()->toRoute('admin_category', ['action' => 'index']);
				} catch (\RuntimeException $exception) {
					$this->flashMessenger()->addErrorMessage($exception->getMessage());
					return $this->redirect()->refresh();
				}
			}
		}

		return new ViewModel([
			'form' => $addcateForm,
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
		$editcateForm = new EditCategoryForm();
		$request = $this->getRequest();
		//Lấy dữ liệu từ model
		$data = $this->theloaiTable->fetchTheloaiById($id);
		if ($this->getRequest()->isPost()) {

			$formData = $request->getPost()->toArray();
			$editcateForm->setInputFilter($this->theloaiTable->getEditCateFilter());
			$editcateForm->setData($formData);

			if ($editcateForm->isValid()) {
				try {
					$cateId = (int) $this->params()->fromRoute('id');
					$datas = $editcateForm->getData();
							
					$this->theloaiTable->updateCate($datas, $cateId);

					$this->flashMessenger()->addSuccessMessage('Danh mục cập nhật thành công');
					return $this->redirect()->toRoute('admin_category', ['action' => 'edit', 'id' => $cateId]);
				} catch (\RuntimeException $exception) {
					$this->flashMessenger()->addErrorMessage($exception->getMessage());
					return $this->redirect()->refresh();
				}
			}
		} else {
			$datas = [
				'theloai' => $data->getTenTheLoai(),

			];
			$editcateForm->setData($datas);
			return new ViewModel([
				'form' => $editcateForm,
				'id' => $id,
			]);
		}
	}


	public function deleteAction()
	{

		$cateId = (int) $this->params()->fromRoute('id');
		if (!is_numeric($cateId) || !$this->theloaiTable->fetchTheloaiById($cateId)) {
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
		$delcateForm = new DeleteCateForm();
		$request = $this->getRequest();
		if ($request->isPost()) {
			$formData = $request->getPost()->toArray();
			$delcateForm->setData($formData);
			if ($delcateForm->isValid()) {
				if ($request->getPost()->get('del_cate') == 'Yes') {
					$this->theloaiTable->deleteCate((int) $cateId);
					$this->flashMessenger()->addSuccessMessage('Xóa danh mục thành công.');
					return $this->redirect()->toRoute('admin_category', ['action' => 'index']);
				}
				# otherwise return to the homepage
				return $this->redirect()->toRoute('admin_category', ['action' => 'index']);
			}
		}
		return new ViewModel(['form' => $delcateForm, 'id' => $cateId]);
	}

}
