<?php

declare(strict_types=1);

namespace News\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use News\Controller\Admin_feedController;
use News\Model\Table\DanhmucfeedTable;

class Admin_feedControllerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		return new Admin_feedController(
			$container->get(DanhmucfeedTable::class)
		);
	}
}
