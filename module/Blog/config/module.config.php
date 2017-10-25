<?php

namespace Blog;

use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'doctrine' => [
        'driver' => [
            'blog_entity' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/Entity',
                ],
            ],
            'orm_default' => [
                'drivers' => [
                    'Entity' => 'blog_entity',
                ]
            ]
        ]
    ],
    'controllers' => [
        'factories' => [
             Controller\IndexController::class => InvokableFactory::class,
        ]
    ],
    'router' => [
        'routes' => [
            'blog' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/',//'[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'    => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];