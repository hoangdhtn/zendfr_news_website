<?php

declare(strict_types=1);

namespace News\Form\Auth;

use Laminas\Form\Form;
use Laminas\Form\Element;

class CreateForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('new_account');
		$this->setAttribute('method', 'post');

		# username input field
		$this->add([
			'type' => Element\Text::class,
			'name' => 'username',
			'options' => [
				'label' => 'Tên tài khoản'
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 25,
				'pattern' => '^[a-zA-Z0-9]+$',  # enforcing what type of data we accept
				'data-toggle' => 'tooltip',
				'class' => 'form-control',   # styling the text field
				'title' => 'Tên tài khoản không được chứa kí tự đặc biệt',
				'placeholder' => 'Nhập tên tài khoản'
			]
		]);

		# gender select field
		$this->add([
			'type' => Element\Select::class,
			'name' => 'gender',
			'options' => [
				'label' => 'Giới tính',
				'empty_option' => 'Chọn...',
				'value_options' => [
					'Female' => 'Nữ',
					'Male' => 'Nam',
					'Other' => 'Khác'
				],
			],
			'attributes' => [
				'required' => true,
				'class' => 'custom-select', # styling
			]
		]);

		# email address input field
		$this->add([
			'type' => Element\Email::class,
			'name' => 'email',
			'options' => [
				'label' => 'Địa chỉ Email'
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 128,
				'pattern' => '^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$',
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'class' => 'form-control',
				'title' => 'Địa chỉ email không khả dụng',
				'placeholder' => 'Nhập email của bạn'
			]
		]);

		# confirm email address
		$this->add([
			'type' => Element\Email::class,
			'name' => 'confirm_email',
			'options' => [
				'label' => 'Nhập lại địa chi email'
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 128,
				'pattern' => '^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$',
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'class' => 'form-control',
				'title' => 'Email nhập lại không đúng',
				'placeholder' => 'Nhập lại email của bạn'
			]
		]);

		# birth day select field
		$this->add([
			'type' => Element\DateSelect::class,
			'name' => 'birthday',
			'options' => [
				'label' => 'Ngày sinh',
				'create_empty_option' => true,
				'max_year' => date('Y') - 13, # here we want users over the age of 13 only
				'render_delimiters' => false,
				'year_attributes' => [
					'class' => 'custom-select w-30'
				],
				'month_attributes' => [
					'class' => 'custom-select w-30'
				],
				'day_attributes' => [
					'class' => 'custom-select w-30',
					'id' => 'day'
				],
			],
			'attributes' => [
				'required' => true
			]
		]);

		# password input field
		$this->add([
			'type' => Element\Password::class,
			'name' => 'password',
			'options' => [
				'label' => 'Mật khẩu'
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 25,
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'class' => 'form-control',   # styling
				'title' => 'Mật khẩu phải từ 8 đến 25 kí tự',
				'placeholder' => 'Nhập mật khẩu'
			]
		]);

		# confirm password input field
		$this->add([
			'type' => Element\Password::class,
			'name' => 'confirm_password',
			'options' => [
				'label' => 'Nhập lại mật khẩu'
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 25,
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'class' => 'form-control',   # styling
				'title' => 'Mật khẩu nhập lại không đúng',
				'placeholder' => 'Nhập lại mật khẩu của bạn'
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
			'name' => 'create_account',
			'attributes' => [
				'value' => 'Đăng kí tài khoản',
				'class' => 'btn btn-primary'
			]
		]);
	}
}

