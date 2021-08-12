<?php

declare(strict_types=1);

namespace News\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use News\Controller\Category_detailController;
use News\Model\Table\TintucTable;
use News\Model\Table\TheloaiTable; 

class Category_detailControllerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		return new Category_detailController(
			$container->get(TintucTable::class),
			$container->get(TheloaiTable::class)
		);
	}
}
