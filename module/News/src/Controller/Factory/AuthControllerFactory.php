<?php

declare(strict_types=1);

namespace News\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use News\Controller\AuthController;
use News\Model\Table\UsersTable;

class AuthControllerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		return new AuthController(
			$container->get(UsersTable::class)
		);
	}
}
