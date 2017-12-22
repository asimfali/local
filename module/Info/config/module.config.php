<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 13.12.2017
 * Time: 13:51
 */

use Info\Controller\IndexController;
use Info\Controller\AdminController;
use Info\Factory\IndexControllerFactory;
use Info\Factory\AdminControllerFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'controllers' => [
        'factories' => [
            IndexController::class => IndexControllerFactory::class,
            AdminController::class => AdminControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'info' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/info/',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'all' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'all/[:action/][:id/]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => IndexController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'admin' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'admin/[:action/][:id/]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => AdminController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                ]
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'template_map' => [
            'info' => __DIR__ . '/../view/info/index/index.phtml',
            'lside' => __DIR__ . '/../../Custom/left-sidebar.phtml',
            'view' => __DIR__ . '/../../Custom/view.phtml',
        ],
    ],
    'PathInfo' => __DIR__ . '/../../../public/content/info/',
];
