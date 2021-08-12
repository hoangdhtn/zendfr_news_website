<?php

declare(strict_types=1);

namespace News\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use News\Controller\CategoryfeedController;
use News\Model\Table\DanhmucfeedTable;

class CategoryfeedControllerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		return new CategoryfeedController(
			$container->get(DanhmucfeedTable::class)
		);
	}
}
