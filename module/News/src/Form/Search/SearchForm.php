<?php

declare(strict_types=1);

namespace News\Form\Search;

use Laminas\Form\ELement;
use Laminas\Form\Form;

class SearchForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('search');
		$this->setAttribute('method', 'post');

		$this->add([
			'type' => Element\Search::class,
			'name' => 'search',
			'options' => [
				'label' => 'Tìm kiếm',
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'class' => 'form-control',
				'title' => 'Tìm kiếm',
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
			'name' => 'tim',
			'attributes' => [
				'class' => 'btn btn-primary',
				'value' => 'Tìm'
			]
		]);
	}
}
