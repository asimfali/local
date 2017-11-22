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
                    'route' => '/izv/[:action/][:id/]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'    => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'pdf' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'pdf/[:action/][:name/]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'name' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'controller' => IndexController::class,
                                'action' => 'show',
                            ]
                        ],
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
    'Path' => __DIR__ . '/../../../public/content/',
    'models' => [
        'fields' => [
            'name' => 'izv',
            'table' => 'fields',
            'desc' => 'Общие данные',
            'getUrl' => [
                'name' => '',
                'count' => '',
                'filterName' => '',
                'filterVal' => '',
            ],
            'query' => [
                'select' => 'a',
                'from' => 'Fields',
                'order' => 'a.id',
                'desc' => 'DESC',
            ],
            'css' => 'table table-striped table-hover',
            'ths' => [
                'Текст' => 'txt','Категория' => ['category','CategoryName'],
                'ДопПоле' => 'code', 'Опубликовано(0/1)' => 'is_public', 
                'Действие' => ['edit' => 'Редактировать', 'delete' => 'Удалить']],
            'entity' => '\\Entity\\Fields',
            'Redirect' => 'izv',
            'MessageError' => 'Ошибка',
            'add' => [
                'Action' => '/izv/add/',
                'MessageError' => 'Ошибка параметров',
                'MessageSuccess' => 'Поле добавлено'
            ],
            'edit' => [
                'Action' => '/izv/edit/',
                'MessageError' => 'Запись не найдена',
                'MessageSuccess' => 'Запись обновлена'
            ],
            'delete' => [
                'Action' => '/izv/delete/',
                'MessageError' => 'Ошибка удаления записи',
                'MessageSuccess' => 'Запись удалена'
            ],
        ],
        'department' => [
            'name' => 'izv',
            'table' => 'department',
            'desc' => 'Отделы',
            'getUrl' => [
                'name' => '',
                'count' => '',
                'filterName' => '',
                'filterVal' => '',
            ],
            'query' => [
                'select' => 'a',
                'from' => 'Department',
                'order' => 'a.id',
                'desc' => 'DESC',
            ],
            'css' => 'table table-striped table-hover',
            'ths' => [
                'Отдел' => 'name', 'Псевдоним' => 'alias',
                'Действие' => ['edit' => 'Редактировать', 'delete' => 'Удалить']],
            'entity' => '\\Entity\\Department',
            'Redirect' => 'izv',
            'MessageError' => 'Ошибка',
            'add' => [
                'Action' => '/izv/add/',
                'MessageError' => 'Ошибка параметров',
                'MessageSuccess' => 'Поле добавлено'
            ],
            'edit' => [
                'Action' => '/izv/edit/',
                'MessageError' => 'Запись не найдена',
                'MessageSuccess' => 'Запись обновлена'
            ],
            'delete' => [
                'Action' => '/izv/delete/',
                'MessageError' => 'Ошибка удаления записи',
                'MessageSuccess' => 'Запись удалена'
            ],
        ],
        'usrAction' => [
            'name' => 'izv',
            'table' => 'usrAction',
            'desc' => 'Действия',
            'getUrl' => [
                'name' => '',
                'count' => '',
                'filterName' => '',
                'filterVal' => '',
            ],
            'query' => [
                'select' => 'a',
                'from' => 'UsrAction',
                'order' => 'a.id',
                'desc' => 'DESC',
            ],
            'css' => 'table table-striped table-hover',
            'ths' => [
                'Роль' => 'usrAction', 'Пользователь' => ['user','usrFirstName'],
                'Действие' => ['edit' => 'Редактировать', 'delete' => 'Удалить']],
            'entity' => '\\Entity\\UsrAction',
            'Redirect' => 'izv',
            'MessageError' => 'Ошибка',
            'add' => [
                'Action' => '/izv/add/',
                'MessageError' => 'Ошибка параметров',
                'MessageSuccess' => 'Поле добавлено'
            ],
            'edit' => [
                'Action' => '/izv/edit/',
                'MessageError' => 'Запись не найдена',
                'MessageSuccess' => 'Запись обновлена'
            ],
            'delete' => [
                'Action' => '/izv/delete/',
                'MessageError' => 'Ошибка удаления записи',
                'MessageSuccess' => 'Запись удалена'
            ],
        ],
        'templates' => [
            'name' => 'izv',
            'table' => 'templates',
            'desc' => 'Шаблоны',
            'itemName' => 'group',
            'getUrl' => [
                'name' => '',
                'count' => '',
                'filterName' => '',
                'filterVal' => '',
            ],
            'query' => [
                'select' => 'a',
                'from' => 'Templates',
                'order' => 'a.id',
                'desc' => 'DESC',
            ],
            'css' => 'table table-striped table-hover',
            'ths' => [
                'Имя' => 'name',
                'Действие' => ['add-item' => 'Добавить','edit' => 'Редактировать']],
            'entity' => '\\Entity\\Templates',
            'refEntity' => '\\Entity\\UsrAction',
            'Redirect' => '/izv/add-item/',
            'MessageError' => 'Ошибка',
            'add' => [
                'Action' => '/izv/add/',
                'MessageError' => 'Ошибка параметров',
                'MessageSuccess' => 'Поле добавлено'
            ],
            'edit' => [
                'Action' => '/izv/edit/',
                'MessageError' => 'Запись не найдена',
                'MessageSuccess' => 'Запись обновлена'
            ],
            'delete' => [
                'Action' => '/izv/delete/',
                'MessageError' => 'Ошибка удаления записи',
                'MessageSuccess' => 'Запись удалена'
            ],
            'add-item' => [
                'Action' => '/izv/add-item/',
                'MessageError' => 'Ошибка добавления в коллекцию',
                'MessageSuccess' => 'Коллекция изменена'
            ],
        ],
    ]
    ];