<?php

declare(strict_types=1);

namespace News\Form\Admin;

use Laminas\Form\ELement;
use Laminas\Form\Form;

class EditnewsForm extends Form
{
	public function __construct($languages, $name = 'edit_news')
	{
		$this->languages = $languages;
		parent::__construct('edit_news');
		$this->setAttribute('method', 'post');

		$this->add([
			'type' => Element\Text::class,
			'name' => 'tieude_news',
			'options' => [
				'label' => 'Tiêu đề',
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'class' => 'form-control',
				'title' => 'Tiêu đề hiện tại',
			]
		]);

		$this->add([
			'type' => Element\TextArea::class,
			'name' => 'tomtat_news',
			'options' => [
				'label' => 'Tóm tắt',
			],
			'attributes' => [
				'required' => true,
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'class' => 'form-control',
				'title' => 'Tóm tắt bài viết',
				'placeholder' => 'Tóm tắt bài viết'
			]
		]);

		$this->add(
		    [
		        'type' => \Laminas\Form\Element\Select::class,
		        'name' => 'theloai',
		        'options' => [
		            'label' => 'Thể loại',
		            'empty_option' => 'Chọn thể loại',
		            'value_options' => $languages
		        ],
		    ]
		);

		$this->add([
			'type' => Element\TextArea::class,
			'name' => 'noidung_news',
			'options' => [
				'label' => 'Nội dung',
			],
			'attributes' => [
				'required' => true,
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'class' => 'form-control ckeditor',
				'title' => 'Nội dung bài viết',
				'placeholder' => 'Nội dung bài viết',
				'rows' => 10,
				'cols' => 80,
				'id' => 'editor1',
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
			'name' => 'capnhat_news',
			'attributes' => [
				'class' => 'btn btn-primary',
				'value' => 'Lưu thay đổi'
			]
		]);
	}
}
