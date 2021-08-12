<?php

declare(strict_types=1);

namespace News\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use News\Controller\PasswordController;
use News\Model\Table\UsersTable;
use News\Model\Table\ForgotTable;

class PasswordControllerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		return new PasswordController(
			$container->get(ForgotTable::class),
			$container->get(UsersTable::class)
		);
	}
}
