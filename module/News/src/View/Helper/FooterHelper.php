<?php

declare(strict_types=1);

namespace News\View\Helper;
use Laminas\Db\Adapter\Adapter;

use Laminas\View\Helper\AbstractHelper;

class FooterHelper extends AbstractHelper
{


	public function __invoke()
	{

		$configArray   = [
        'driver'   => 'Mysqli',
		    'username' => 'root',
		    'password' => '',
		    'database' => 'news',
		    'hostname' => 'localhost',
		    'charset'  => 'utf8', //latin1
		    ];

		    $dbadapter = new \Zend\Db\Adapter\Adapter($configArray);
		    $query = "select * from theloai limit 5";

		    $results = $dbadapter->query($query,
		        \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

		    $categories = [];
		    
		    foreach ($results as $item) {
		        $categories[] = $item;
		    }

		return $categories;
	}

}