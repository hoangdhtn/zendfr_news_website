<?php

declare(strict_types=1);

namespace News\Form\Feed;

use Laminas\Form\ELement;
use Laminas\Form\Form;

class AddFeedForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('add_feed');
		$this->setAttribute('method', 'post');

		$this->add([
			'type' => Element\Text::class,
			'name' => 'ten',
			'options' => [
				'label' => 'Tên',
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'class' => 'form-control',
				'title' => 'Tên',
			]
		]);

		$this->add([
			'type' => Element\Text::class,
			'name' => 'link',
			'options' => [
				'label' => 'Link',
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'class' => 'form-control',
				'title' => 'Link',
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
			'name' => 'them',
			'attributes' => [
				'class' => 'btn btn-primary',
				'value' => 'Xác nhận'
			]
		]);
	}
}
