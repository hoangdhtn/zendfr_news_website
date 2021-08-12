<?php

declare(strict_types=1);

namespace News\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Db\Adapter\Adapter;


use News\Controller\LoginController;
use News\Model\Table\UsersTable;

class LoginControllerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		return new LoginController(
			$container->get(Adapter::class),
			$container->get(UsersTable::class)
		);
	}
}

