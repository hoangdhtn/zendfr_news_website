<?php

declare(strict_types=1);

namespace News\Form\Auth;

use Laminas\Form\Element;
use Laminas\Form\Form;

class ResetForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('alter_password');
		$this->setAttribute('method', 'post');

		# new password input field
		$this->add([
			'type' => Element\Password::class,
			'name' => 'new_password',
			'options' => [
				'label' => 'Mật khẩu mới'
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 25,
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'class' => 'form-control',   # styling
				'title' => 'Mật khẩu phải có từ 8 đến 25 ký tự',
				'placeholder' => 'Nhập mật khẩu mới của bạn'
			]
		]);

		# confirm new password input field
		$this->add([
			'type' => Element\Password::class,
			'name' => 'confirm_new_password',
			'options' => [
				'label' => 'Xác nhận mật khẩu mới'
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 25,
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'class' => 'form-control',   # styling
				'title' => 'Mật khẩu phải khớp với mật khẩu được cung cấp ở trên',
				'placeholder' => 'Nhập lại mật khẩu mới của bạn'
			]
		]);

		# cross-site-request forgery (csrf) field
		$this->add([
			'type' => Element\Csrf::class,
			'name' => 'csrf',
			'options' => [
				'csrf_options' => [
					'timeout' => 600,  # 5 minutes
				]
			]
		]);

		# submit button
		$this->add([
			'type' => Element\Submit::class,
			'name' => 'reset_password',
			'attributes' => [
				'value' => 'Đặt lại mật khẩu',
				'class' => 'btn btn-primary'
			]
		]);
	}
}
