<?php

declare(strict_types=1);

namespace News\Form\Feed;

use Laminas\Form\Element;
use Laminas\Form\Form;

class DelFeedForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('del_feed');
		$this->setAttribute('method', 'post');

		$this->add([
			'type' => Element\Hidden::class,
			'name' => 'feed_id'
		]);

		$this->add([
			'type' => Element\Submit::class,
			'name' => 'del_feed',
			'attributes' => [
				'class' => 'btn btn-primary'
			]
		]);
	}
}
