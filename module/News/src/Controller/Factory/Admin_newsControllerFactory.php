<?php

declare(strict_types=1);

namespace News\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use News\Controller\Admin_newsController;
use News\Model\Table\TintucTable;
use News\Model\Table\TheloaiTable;

class Admin_newsControllerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		return new Admin_newsController(
			$container->get(TintucTable::class),
			$container->get(TheloaiTable::class)
		);
	}
}
