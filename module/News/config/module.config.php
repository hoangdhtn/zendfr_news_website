<?php

declare(strict_types=1);

namespace News;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\HomeController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'trangchu' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/index[/page/:page]',
                    'defaults' => [
                        'controller' => Controller\HomeController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'detail' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/detail[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\DetailController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'categorydetail' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/categorydetail[/:action[/:id[/page[/:page]]]]',
                    'defaults' => [
                        'controller' => Controller\Category_detailController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'categoryfeed' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/categoryfeed[/:action[/:id[/page[/:page]]]]',
                    'defaults' => [
                        'controller' => Controller\CategoryfeedController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'search' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/search[/:action[/:id[/page[/:page]]]]',
                    'defaults' => [
                        'controller' => Controller\SearchController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'signup' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/signup',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'create',
                    ],
                ],
            ],
            'login' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/login',
                    'defaults' => [
                        'controller' => Controller\LoginController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'logout' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/logout',
                    'defaults' => [
                        'controller' => Controller\LogoutController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'forgot' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/forgot',
                    'defaults' => [
                        'controller' => Controller\PasswordController::class,
                        'action'     => 'forgot',
                    ],
                ],
            ],
            'profile' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/profile[/:id[/:username]]',
                    'constraints' => [
                        'id' => '[0-9]+',
                        'username' => '[a-zA-Z][a-zA-Z0-9_-]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\ProfileController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'reset' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/reset[/:id[/:token]]',
                    'constraints' => [
                        'id' => '[0-9]+',
                        'token' => '[a-zA-Z][a-zA-Z0-9_-]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\PasswordController::class,
                        'action'     => 'reset',
                    ],
                ],
            ],
            'help' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/help[/:action]',
                    'constraints' => [
                        'action' => '(contact|privacy|terms)',
                    ],
                    'defaults' => [
                        'controller' => Controller\HelpController::class,
                    ],
                ],
            ],
            'settings' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/settings[/:action[/:id]]',
                    'constraints' => [
                        'id' => '[0-9]+',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\SettingController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'admin_user' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin/user[/:action[/:id[/page[/:page]]]]', 
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]+',
                        'id' => '[0-9]+',
                        'page' => '[0-9]+',

                    ],
                    'defaults' => [
                        'controller' => Controller\AdminController::class,
                        'action' => 'index'
                    ],
                ],
            ],
            'admin_news' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin/news[/:action[/:id[/page[/:page]]]]', 
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]+',
                        'id' => '[0-9]+',
                        'page' => '[0-9]+',

                    ],
                    'defaults' => [
                        'controller' => Controller\Admin_newsController::class,
                        'action' => 'index'
                    ],
                ],
            ],
            'admin_category' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin/category[/:action[/:id[/page[/:page]]]]', 
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]+',
                        'id' => '[0-9]+',
                        'page' => '[0-9]+',

                    ],
                    'defaults' => [
                        'controller' => Controller\Admin_categoryController::class,
                        'action' => 'index'
                    ],
                ],
            ],
            'admin_feed' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin/feed[/:action[/:id[/page[/:page]]]]', 
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]+',
                        'id' => '[0-9]+',
                        'page' => '[0-9]+',

                    ],
                    'defaults' => [
                        'controller' => Controller\Admin_feedController::class,
                        'action' => 'index'
                    ],
                ],
            ],
            
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\AuthController::class => Controller\Factory\AuthControllerFactory::class,
            Controller\LoginController::class => Controller\Factory\LoginControllerFactory::class,
            # Home
            Controller\HomeController::class => Controller\Factory\HomeControllerFactory::class,
            Controller\SearchController::class => Controller\Factory\SearchControllerFactory::class,
            Controller\Category_detailController::class => Controller\Factory\Category_detailControllerFactory::class,
            # Detail
            Controller\DetailController::class => Controller\Factory\DetailControllerFactory::class,
            # Setting
            Controller\PasswordController::class => Controller\Factory\PasswordControllerFactory::class,
            Controller\SettingController::class => Controller\Factory\SettingControllerFactory::class,
            # Admin
            Controller\AdminController::class => Controller\Factory\AdminControllerFactory::class,
            Controller\Admin_newsController::class => Controller\Factory\Admin_newsControllerFactory::class,
            Controller\Admin_categoryController::class => Controller\Factory\Admin_categoryControllerFactory::class,
            Controller\Admin_feedController::class => Controller\Factory\Admin_feedControllerFactory::class,
            #
            Controller\ProfileController::class => Controller\Factory\ProfileControllerFactory::class,
            Controller\LogoutController::class => InvokableFactory::class,
            Controller\HelpController::class => InvokableFactory::class,   
            # Feed Rss
            Controller\CategoryfeedController::class => Controller\Factory\CategoryfeedControllerFactory::class,
        ],
    ],
    'view_manager' => [
        // 'display_not_found_reason' => true,
        // 'display_exceptions'       => true,
        // 'doctype'                  => 'HTML5',
        // 'not_found_template'       => 'error/404',
        // 'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            # admin new template
            'admin/index'           => __DIR__ . '/../view/admin/index.phtml',
            'admin/delete'           => __DIR__ . '/../view/admin/delete.phtml',
            'adminnews/index'           => __DIR__ . '/../view/adminnews/index.phtml',
            'adminnews/edit'           => __DIR__ . '/../view/adminnews/edit.phtml',
            'adminnews/delete'           => __DIR__ . '/../view/adminnews/delete.phtml',
            'adminnews/add'           => __DIR__ . '/../view/adminnews/add.phtml',
            # admin category template
            'admincategory/index'           => __DIR__ . '/../view/admincategory/index.phtml',
            'admincategory/edit'           => __DIR__ . '/../view/admincategory/edit.phtml',
            'admincategory/delelte'           => __DIR__ . '/../view/admincategory/delelte.phtml',
             # admin feed template
            'adminfeed/index'           => __DIR__ . '/../view/adminfeed/index.phtml',
            'adminfeed/add'           => __DIR__ . '/../view/adminfeed/add.phtml',
            'adminfeed/edit'           => __DIR__ . '/../view/adminfeed/edit.phtml',
            'adminfeed/delete'           => __DIR__ . '/../view/adminfeed/delete.phtml',
            # auth template
            'auth/create'           => __DIR__ . '/../view/auth/create.phtml',
            'login/index'           => __DIR__ . '/../view/auth/login.phtml',
            'profile/index'           => __DIR__ . '/../view/profile/index.phtml',
            'password/forgot'           => __DIR__ . '/../view/auth/forgot.phtml',
            'password/reset'           => __DIR__ . '/../view/auth/reset.phtml',
            # help template
            'news/contact' => __DIR__ . '/../view/help/contact.phtml',
            'news/privacy' => __DIR__ . '/../view/help/privacy.phtml',
            'news/terms' => __DIR__ . '/../view/help/terms.phtml',
            'news/categorydetail' => __DIR__ . '/../view/categorydetail/index.phtml',
            #setting template
            'setting/index' => __DIR__ . '/../view/setting/index.phtml',
            'setting/password' => __DIR__ . '/../view/setting/password.phtml',
            'setting/username' => __DIR__ . '/../view/setting/username.phtml',
            'setting/delete' => __DIR__ . '/../view/setting/delete.phtml',
            // 'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            // 'error/404'               => __DIR__ . '/../view/error/404.phtml',
            // 'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
