<?php

declare(strict_types=1);

namespace News;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;

use News\Model\Table\UsersTable;
use News\Model\Table\TintucTable;
use News\Model\Table\ForgotTable;
use News\Model\Table\RolesTable;
use News\Model\Table\DanhmucfeedTable;

use News\Plugin\AuthPlugin;
use News\Plugin\Factory\AuthPluginFactory;

use News\View\Helper\AuthHelper;

class Module
{
    public function getConfig(): array
    {
        /** @var array $config */
        $config = include __DIR__ . '/../config/module.config.php';
        return $config;
    }

    public function getServiceConfig(): array 
    {
        return [
            'factories' => [
                UsersTable::class => function($sm) {
                    $dbAdapter = $sm->get(Adapter::class);
                    return new UsersTable($dbAdapter);
                },
                 TintucTable::class => function($sm) {
                    $dbAdapter = $sm->get(Adapter::class);
                    return new TintucTable($dbAdapter);
                },
                ForgotTable::class => function($sm) {
                    $dbAdapter = $sm->get(Adapter::class);
                    return new ForgotTable($dbAdapter);
                },
                RolesTable::class => function($sm) {
                    $dbAdapter = $sm->get(Adapter::class);
                    return new RolesTable($dbAdapter);
                },
                DanhmucfeedTable::class => function($sm) {
                    $dbAdapter = $sm->get(Adapter::class);
                    return new DanhmucfeedTable($dbAdapter);
                },
            ]
        ];
    }

    # let the framework know about your plugin
    public function getControllerPluginConfig()
    {
        return [
            'aliases' => [
                'authPlugin' => AuthPlugin::class,
            ],
            'factories' => [
                AuthPlugin::class => AuthPluginFactory::class
            ],
        ];
    }

    # let the service_manager know about your helper
    public function getViewHelperConfig()
    {
        return [
            'aliases' => [
                'authHelper' => AuthHelper::class,
            ],
            'factories' => [
                AuthHelper::class => AuthPluginFactory::class
            ],
            'invokables' => [
                'theloaiHelper' => '\News\View\Helper\TheLoaiHelper', 
                'Banner' => '\News\View\Helper\Banner',
                'footerHelper' => '\News\View\Helper\FooterHelper'
            ],
        ];
    }
}
