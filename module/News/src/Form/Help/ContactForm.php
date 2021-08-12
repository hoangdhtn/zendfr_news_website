<?php

declare(strict_types=1);

namespace News\Form\Help;

use Laminas\Captcha\Recaptcha;
use Laminas\Form\Element;
use Laminas\Form\Form;

class ContactForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('get_in_touch');
		$this->setAttribute('method', 'post');

		$this->add([
			'type' => Element\Text::class,
			'name' => 'your_name',
			'options' => [
				'label' => 'Tên',
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 50,
				'class' => 'form-control',
				'data-toggle' => 'tooltip',
				'title' => 'Tên đầy đủ của bạn',
				'placeholder' => 'Nhập họ và tên',
			]
		]);

		$this->add([
			'type' => Element\Email::class,
			'name' => 'your_email',
			'options' => [
				'label' => 'Email',
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 128,
				'pattern' => '^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$',
				'class' => 'form-control',
				'data-toggle' => 'tooltip',
				'title' => 'Nhập email',
				'placeholder' => 'Nhập email của bạn',
			]
		]);

		$this->add([
			'type' => Element\Textarea::class,
			'name' => 'message',
			'options' => [
				'label' => 'Message Content',
			],
			'attributes' => [
				'required' => true,
				'cols' => 90,
				'maxlength' => 2500,
				'class' => 'form-control',
				'data-toggle' => 'tooltip',
				'title' => 'Lời nhắn',
				'placeholder' => 'Lời nhắn của bạn',
			]
		]);


		$this->add([
			'type' => Element\Captcha::class,
			'name' => 'turing',
			'options' => [
				'label' => 'Chứng minh bạn là con người',
				'captcha' => new Recaptcha([
					#'secret_key' => '6LdL4EAUAAAAAHs48MK7DuPNBNKmvwruirzAYigF', # this was for example.com
					#
					#'site_key' => '6LdL4EAUAAAAABXDX9EPvyPR949BUmwi-ItrcOVQ',

					'secret_key' => '6LeCwhYbAAAAAKtEpKlSz45soxMsrNQzYzp1Obkl',
					'site_key' => '6LeCwhYbAAAAAPpQDVngscFRUj5tqxm0ERzd0MOS',
				]),
			],
			'attributes' => [
				'required' => true,
				'class' => 'form-control'
			]
		]);

		$this->add([
			'type' => Element\Csrf::class,
			'name' => 'csrf',
			'options' => [
				'csrf_options' => [
					'timeout' => 600,
				],
			],
		]);

		$this->add([
			'type' => Element\Submit::class,
			'name' => 'contact_us',
			'attributes' => [
				'value' => 'Gửi',
				'class' => 'btn btn-primary'
			]
		]);
	}
}
