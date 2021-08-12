<?php

declare(strict_types=1);

namespace News\Controller;

use Zend\Mvc\MvcEvent;
use Laminas\Authentication\AuthenticationService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\File\Transfer;

use News\Model\Table\TintucTable;
use News\Form\Search\SearchForm;


class SearchController extends AbstractActionController
{
	private $tintucTable;


	public function __construct(TintucTable $tintucTable)
	{
		$this->tintucTable = $tintucTable;
	}

	public function indexAction()
	{
		// $search = new SearchForm();

		// return new ViewModel([
		// 	'form' => $search

		// ]);

		$addcateForm = new SearchForm();
		$request = $this->getRequest();
		$hh = array();

		if ($this->getRequest()->isPost()) {

			$formData = $request->getPost()->toArray();
			$addcateForm->setInputFilter($this->tintucTable->getSearchnewsFilter());
			$addcateForm->setData($formData);

			if ($addcateForm->isValid()) {
				try {
					$datas = $addcateForm->getData();

					$hh = $this->tintucTable->fetchNewsKeyWord($datas['search']);
					return new ViewModel([
					'form' => $addcateForm,
					'dd' => $hh
				]);
					
					//return $this->redirect()->toRoute('admin_category', ['action' => 'index']);
				} catch (\RuntimeException $exception) {
					$this->flashMessenger()->addErrorMessage($exception->getMessage());
					return $this->redirect()->refresh();
				}
			}
		}

		return new ViewModel([
			'form' => $addcateForm,
			'dd' => []
		]);

		
		

	}

}
