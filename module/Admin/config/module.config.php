<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 18.10.2017
 * Time: 12:21
 */
namespace Admin\Controller;
use Admin\Factory\ArticleControllerFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Admin\Factory\CategoryControllerFactory;

return [

    'controllers' => [
        'factories' => [
            IndexController::class => InvokableFactory::class,
            CategoryController::class => CategoryControllerFactory::class,
            ArticleController::class => ArticleControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'admin' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/admin/',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'category' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'category/[:action/][:id/]',
                            'defaults' => [
                                'controller' => CategoryController::class,
                                'action' => 'index',
                            ]
                        ]
                    ],
                    'article' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'article/[:action/][:id/]',
                            'defaults' => [
                                'controller' => ArticleController::class,
                                'action' => 'index',
                            ]
                        ]
                    ],
            ] //child routes
            ],

//            'child_routes' => [
//                'category' => [
//                    'type' => Segment::class,
//                    'options' => [
//                        'route' => 'category/[:action/][:id/]',
//                        'defaults' => [
//                            'controller' => CategoryController::class,
//                            'action' => 'index',
//                        ]
//                    ]
//                ]
//            ] //child routes
        ]
    ],
    'navigation' => [
        'default' => [
            [
                'label' => 'Главная',
                'route' => 'home',
            ],
        ],
        'admin_navigation' => [
            [
                'label' => 'Панель управления сайтом',
                'route' => 'admin',
                'action' => 'index',
                'resource' => 'Admin\\Controller\\Index',
                'pages' => [
                    [
                        'label' => 'Статьи',
                        'route' => 'admin/article',
                        'action' => 'index',
                    ],
                    [
                        'label' => 'Добавить статью',
                        'route' => 'admin/article',
                        'action' => 'add',
                    ],
                    [
                        'label' => 'Категории',
                        'route' => 'admin/category',
                        'action' => 'index',
                    ],
                    [
                        'label' => 'Добавить категорию',
                        'route' => 'admin/category',
                        'action' => 'add',
                    ],
//                    [
//                        'label' => 'Добавить комментарий',
//                        'route' => 'admin/comment',
//                        'action' => 'index',
//                    ]
                ]
            ]
        ]
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'template_map' => [
            'pagination_control' => __DIR__ . '/../view/layout/pagination_control.phtml'
        ]
    ],
    'service_manager' => [
        'factories' => [
            'navigation' => 'Zend\\Navigation\\Service\\DefaultNavigationFactory',
            'admin_navigation' => 'Admin\\Lib\\AdminNavigationFactory'
        ]
    ]
];
