<?php

declare(strict_types=1);

namespace News\View\Helper;
use Zend\Db\Adapter\Adapter;

use Laminas\View\Helper\AbstractHelper;

class Banner extends AbstractHelper
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
		    $query = "select * from tintuc A left join theloai B on A.idTheLoai=B.id order by A.id DESC limit 3";

		    $results = $dbadapter->query($query,
		        \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

		    $banner = [];
		    
		    foreach ($results as $item) {
		        $banner[] = $item;
		    }

		return $banner;
	}

}