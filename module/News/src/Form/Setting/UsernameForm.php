<?php

declare(strict_types=1);

namespace News\Form\Setting;

use Laminas\Form\ELement;
use Laminas\Form\Form;

class UsernameForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('update_username');
		$this->setAttribute('method', 'post');

		$this->add([
			'type' => Element\Text::class,
			'name' => 'current_username',
			'options' => [
				'label' => 'Tên người dùng hiện tại',
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 25,
				'pattern' => '^[a-zA-Z0-9]+$',
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'class' => 'form-control',
				'title' => 'Tên người dùng hiện tại',
				'readonly' => true,	
			]
		]);

		$this->add([
			'type' => Element\Text::class,
			'name' => 'new_username',
			'options' => [
				'label' => 'Tên người dùng mới',
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 25,
				'pattern' => '^[a-zA-Z0-9]+$',
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'class' => 'form-control',
				'title' => 'Tên người dùng chỉ được bao gồm các ký tự chữ và số',
				'placeholder' => 'Nhập tên người dùng ưa thích của bạn'
			]
		]);


		$this->add([
			'type' => Element\Csrf::class,
			'name' => 'csrf',
			'options' => [
				'csrf_options' => [
					'timeout' => 300
				],
			],
		]);


		$this->add([
			'type' => Element\Submit::class,
			'name' => 'change_username',
			'attributes' => [
				'class' => 'btn btn-primary',
				'value' => 'Lưu thay đổi'
			]
		]);
	}
}
