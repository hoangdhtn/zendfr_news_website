<?php

declare(strict_types=1);

namespace News\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use News\Controller\Admin_categoryController;

use News\Model\Table\TheloaiTable;

class Admin_categoryControllerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		return new Admin_categoryController(
			$container->get(TheloaiTable::class)
		);
	}
}
