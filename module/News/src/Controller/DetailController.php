<?php

declare(strict_types=1);

namespace News\Controller;

use Zend\Mvc\MvcEvent;
use Laminas\Authentication\AuthenticationService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use News\Model\Table\TintucTable;

class DetailController extends AbstractActionController
{
	private $tintucTable;

	public function __construct(TintucTable $tintucTable)
	{
		$this->tintucTable = $tintucTable;
	}

	public function indexAction()
	{
		$newsId = (int)$this->params('id');
		$data = $this->tintucTable->fetchNewsById($newsId); 

		return new ViewModel([
			'datas' => $data
		]);
	}
}
