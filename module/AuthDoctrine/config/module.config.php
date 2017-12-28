<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 26.10.2017
 * Time: 9:52
 */
namespace AuthDoctrine;
use AuthDoctrine\Controller\IndexController;
use AuthDoctrine\Factory\AuthDoctrineFactory;
use Entity\User;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [

    'controllers' => [
        'factories' => [
            IndexController::class => AuthDoctrineFactory::class,
        ],
        'aliases' => [
            'index' => 'AuthDoctrine\Controller\IndexController',
        ]
    ],
    'router' => [
        'routes' => [
            'auth-doctrine' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/auth-doctrine/',
                    'defaults' => [
                        '__NAMESPACE__' => 'AuthDoctrine\\Controller',
                        'controller' => IndexController::class,
                        'action' => 'login',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'default' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '[:controller/[:action/[:id/]]]',
                            'constraints' => [
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                            ]
                        ]
                    ],
                ] //child routes
            ],
        ]
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
            __DIR__ . '/../../Views',
        ],
        'display_exceptions' => true,
    ],
    'doctrine' => [
        'authentication' => [
            'orm_default' => [
                'identity_class' => User::class,
                'identity_property' => 'usrName',
                'credential_property' => 'usrPassword',
                'credential_callable' => function(User $user, $password){
                    if ($user->getUsrPassword() == md5('staticSalt' . $password . $user->getUsrPasswordSalt())){
//                    if ($user->getUsrPassword() == $password){
                        return true;
                    } else {
                        return false;
                    }
                }
            ]
        ]
    ]
];
