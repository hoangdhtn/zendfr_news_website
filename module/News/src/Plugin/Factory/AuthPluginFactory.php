<?php

declare(strict_types=1);

namespace News\Plugin\Factory;

use Interop\Container\ContainerInterface;
use Laminas\Authentication\AuthenticationService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use News\Model\Table\UsersTable;
use News\Plugin\AuthPlugin;

class AuthPluginFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		return new AuthPlugin(
			$container->get(AuthenticationService::class),
			$container->get(UsersTable::class)
		);
	}
}
