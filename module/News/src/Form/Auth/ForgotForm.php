<?php

declare(strict_types=1);

namespace News\Form\Auth;

use Laminas\Captcha\Image;
use Laminas\Form\Element;
use Laminas\Form\Form;

class ForgotForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('lost_password');
		$this->setAttribute('method', 'post');

		# email input field
		$this->add([
			'type' => Element\Email::class,
			'name' => 'email',
			'options' => [
				'label' => 'Địa chỉ email',
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 128,
				'pattern' => '^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$',
				'autocomplete' => false,
				'class' => 'form-control',
				'title' => 'Cung cấp địa chỉ email của tài khoản của bạn',
				'placeholder' => 'Nhập địa chỉ email của bạn'
			]
		]);

		# captcha field
		$this->add([
			'type' => Element\Captcha::class,
			'name' => 'turing',
			'options' => [
				'label' => 'Xác minh rằng bạn là con người?',
				'captcha' => new Image([
					'font' => DOOR.DS.'/fonts/ubi.ttf',
					'imgUrl' => '/news/public/images/captcha/',
					'fsize' => 55,
					'wordLen' => 6,
					'imgAlt' => 'images captcha',
					'height' => 100,
					'width' => 300,
					'dotNoiseLevel' => 220,
					'lineNoiseLevel' => 18,
				]),
			],
			'attributes' => [
				'size' => 40,
				'required' => true,
				'maxlength' => 6,
				'pattern' => '^[a-zA-Z0-9]+$',
				'class' => 'custom-control',
				'data-toggle' => 'tooltip',
				'title' => 'Provide text displayed',
				'placeholder' => 'Type in characters displayed',
				'captcha' => (new Element\Captcha())->getInputSpecification(), # validation
			],
		]);

		# csrf field
		$this->add([
			'type' => Element\Csrf::class,
			'name' => 'csrf',
			'options' => [
				'csrf_options' => [
					'timeout' => 600,
				],
			],
		]);

		# submit button
		$this->add([
			'type' => Element\Submit::class,
			'name' => 'forgot_password',
			'attributes' => [
				'value' => 'Gửi tin nhắn',
				'class' => 'btn btn-primary'
			]
		]);
	}
}
