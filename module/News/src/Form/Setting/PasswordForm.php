<?php

declare(strict_types=1);

namespace News\Form\Setting;

use Laminas\Form\Element;
use Laminas\Form\Form;

class PasswordForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('update_password');
		$this->setAttribute('method', 'post');


		# current password field
		$this->add([
			'type' => Element\Password::class,
			'name' => 'current_password',
			'options' => [
				'label' => 'Mật khẩu hiện tại'
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 25,
				'class' => 'form-control',
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'title' => 'Cung cấp mật khẩu hiện tại của tài khoản của bạn',
				'placeholder' => 'Nhập mật khẩu hiện tại của bạn'
			]
		]);

		#new_password field
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
				'class' => 'form-control',
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'title' => 'Mật khẩu phải có ít nhất 8 ký tự',
				'placeholder' => 'Nhập mật khẩu mới của bạn'
			]
		]);

		#confirm_new_password field
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
				'class' => 'form-control',
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'title' => 'Mật khẩu phải khớp với mật khẩu được cung cấp ở trên',
				'placeholder' => 'Nhập lại mật khẩu mới của bạn'
			]
		]);

		$this->add([
			'type' => Element\Csrf::class,
			'name' => 'csrf',
			'options' => [
				'csrf_options' => [
					'timeout' => 600
				],
			],
		]);

		$this->add([
			'type' => Element\Submit::class,
			'name' => 'change_password',
			'attributes' => [
				'class' => 'btn btn-primary',
				'value' => 'Lưu thay đổi'
			]
		]);
	}
}
