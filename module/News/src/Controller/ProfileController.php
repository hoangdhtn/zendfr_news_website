<?php

declare(strict_types=1);

namespace News\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use News\Model\Table\UsersTable;

class ProfileController extends AbstractActionController
{
	private $usersTable;

	public function __construct(UsersTable $usersTable)
	{
		$this->usersTable = $usersTable;
	}

	public function indexAction()
	{
		# ok now I am in the ProfileCOntroller
		# the reason I am here is because I want us to now display the profile picture

		$id = (int) $this->params()->fromRoute('id');
		$info = $this->usersTable->fetchAccountById($id);
		if(!$info) {
			return $this->notFoundAction();
		}

    	return new ViewModel(['data' => $info]);
	}
}
