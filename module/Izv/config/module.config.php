<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 10.11.2017
 * Time: 13:11
 */
use Izv\Controller\IndexController;
use Izv\Factory\IndexControllerFactory;
use Zend\Router\Http\Segment;

return [
    'controllers' => [
    'factories' => [
        IndexController::class => IndexControllerFactory::class,
    ]
    ],
    'router' => [
    'routes' => [
        'izv' => [
            'type' => Segment::class,
            'options' => [
                'route' => '/[:action/][:id/]',
                'constraints' => [
                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    'id'    => '[0-9]+',
                ],
                'defaults' => [
                    'controller' => IndexController::class,
                    'action' => 'index',
                ],
            ],
        ],
    ],
    ],
    ];