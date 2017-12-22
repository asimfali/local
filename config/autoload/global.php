<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return [
    // Настройка кэша
    'caches' => [
        'FilesystemCache' => [
            'adapter' => [
                'name' => \Zend\Cache\Storage\Adapter\Filesystem::class,
                'options' => [
                    'cache_dir' => './data/cache',
                    'ttl' => 60*60*1,
                ],
            ],
            'plugins' => [
                [
                    'name' => 'serialiser',
                    'options' => [

                    ],
                ],
            ],
        ],
    ],
    // Настройка сессии.
    'session_config' => [
        // Срок действия cookie сессии истечет через 1 час.
        'cookie_lifetime' => 60*60*24*15,
        // Данные сессии будут храниться на сервере до 30 дней.
        'gc_maxlifetime'     => 60*60*24*30,
    ],
    // Настройка менеджера сессий.
    'session_manager' => [
        // Валидаторы сессии (используются для безопасности).
        'validators' => [
//            \Zend\Session\Validator\RemoteAddr::class,
//            \Zend\Session\Validator\HttpUserAgent::class,
        ]
    ],
    // Настройка хранилища сессий.
    'session_storage' => [
        'type' => \Zend\Session\Storage\SessionArrayStorage::class
    ],
    'navigation' => [
        'default' => [
//            [
//                'label' => 'Главная',
//                'route' => 'home',
//            ],
//            [
//                'label' => 'Вход',
////                'uri' => 'index/login/',
//                'route' => 'auth-doctrine',
////                'controller' => 'index',
////                'action' => 'login',
//            ],
            [
                'label' => 'Извещения',
                'route' => 'izv/all',
                'action' => 'index',
            ],
            [
                'label' => 'Паспорта',
                'route' => 'passport/all',
                'action' => 'index',
            ],
            [
                'label' => 'Инфо',
                'route' => 'info/all',
                'action' => 'index',
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
                        'label' => 'Главная',
                        'route' => 'home',
                    ],
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
                    [
                        'label' => 'Комментарии',
                        'route' => 'admin/comment',
                        'action' => 'index',
                    ],
                ]
            ]
        ]
    ],
    'service_manager' => [
        'abstract_factories' => [
            Zend\Navigation\Service\NavigationAbstractServiceFactory::class,
        ],
    ],
];
