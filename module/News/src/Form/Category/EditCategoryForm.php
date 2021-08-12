<?php

declare(strict_types=1);

namespace News\Form\Category;

use Laminas\Form\ELement;
use Laminas\Form\Form;

class EditCategoryForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('add_category');
		$this->setAttribute('method', 'post');

		$this->add([
			'type' => Element\Text::class,
			'name' => 'theloai',
			'options' => [
				'label' => 'Tên thể loại',
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'class' => 'form-control',
				'title' => 'Tên thể loại',
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
			'name' => 'capnhattheloai',
			'attributes' => [
				'class' => 'btn btn-primary',
				'value' => 'Lưu'
			]
		]);
	}
}
