<?php

declare(strict_types=1);

namespace News\Controller;

use News\Form\Help\ContactForm;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use News\Plugin\AuthPlugin;

class HelpController extends AbstractActionController
{
	public function contactAction()
	{
		$contactForm = new ContactForm();
		return new ViewModel(['form' => $contactForm]);
	}

	public function privacyAction()
	{
		// return new ViewModel([
		// 	'myName' => $this->authPlugin()->getUsername()
		// ]);
		return new ViewModel();
	}

	public function termsAction()
	{
		return new ViewModel();
	}
}
