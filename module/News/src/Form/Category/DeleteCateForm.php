<?php

declare(strict_types=1);

namespace News\Form\Category;

use Laminas\Form\Element;
use Laminas\Form\Form;

class DeleteCateForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('deactivate_account');
		$this->setAttribute('method', 'post');

		$this->add([
			'type' => Element\Hidden::class,
			'name' => 'cate_id'
		]);

		$this->add([
			'type' => Element\Submit::class,
			'name' => 'del_cate',
			'attributes' => [
				'class' => 'btn btn-primary'
			]
		]);
	}
}
