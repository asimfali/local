<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 10.11.2017
 * Time: 13:11
 */
use Izv\Controller\IndexController;
use Izv\Controller\AdminController;
use Izv\Factory\AdderControllerFactrory;
use Izv\Factory\AdminControllerFactory;
use Zend\Router\Http\Segment;
use Zend\Router\Http\Literal;

return [
    'controllers' => [
        'factories' => [
            IndexController::class => AdderControllerFactrory::class,
            AdminController::class => AdminControllerFactory::class,
        ]
    ],
    'router' => [
        'routes' => [
            'izv' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/izv/',
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
                                'id'    => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => IndexController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
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
                    'admin' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'admin/[:action/][:id/]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'    => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => AdminController::class,
                                'action' => 'index',
                            ],
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
            'name' => 'izv/admin',
            'admin' => '/admin',
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
            'Redirect' => 'izv/admin',
            'MessageError' => 'Ошибка',
            'add' => [
                'Action' => '/izv/admin/add/',
                'css' => 'btn btn-success',
                'MessageError' => 'Ошибка параметров',
                'MessageSuccess' => 'Поле добавлено'
            ],
            'edit' => [
                'Action' => '/izv/admin/edit/',
                'MessageError' => 'Запись не найдена',
                'MessageSuccess' => 'Запись обновлена'
            ],
            'delete' => [
                'Action' => '/izv/admin/delete/',
                'MessageError' => 'Ошибка удаления записи',
                'MessageSuccess' => 'Запись удалена'
            ],
        ],
        'user' => [
            'name' => 'izv/admin',
            'admin' => '/admin',
            'table' => 'user',
            'desc' => 'Пользователи',
            'getUrl' => [
                'name' => '',
                'count' => '',
                'filterName' => '',
                'filterVal' => '',
            ],
            'query' => [
                'select' => 'a',
                'from' => 'User',
                'order' => 'a.id',
                'desc' => 'DESC',
            ],
            'css' => 'table table-striped table-hover',
            'ths' => [
                'Фамилия' => 'usrFirstName','Имя' => 'usrMiddleName', 'Отчество' => 'usrLastName'
            ],
            'entity' => '\\Entity\\User',
            'Redirect' => 'izv/admin',
            'MessageError' => 'Ошибка',
            'add' => [
                'Action' => '/izv/admin/add/',
                'css' => 'btn btn-success',
                'MessageError' => 'Ошибка параметров',
                'MessageSuccess' => 'Поле добавлено'
            ],
            'edit' => [
                'Action' => '/izv/admin/edit/',
                'MessageError' => 'Запись не найдена',
                'MessageSuccess' => 'Запись обновлена'
            ],
            'delete' => [
                'Action' => '/izv/admin/delete/',
                'MessageError' => 'Ошибка удаления записи',
                'MessageSuccess' => 'Запись удалена'
            ],
        ],
        'department' => [
            'name' => 'izv/admin',
            'admin' => '/admin',
            'table' => 'department',
            'desc' => 'Отделы',
            'noCopy' => '1',
            'itemName' => 'usrDepartment',
            'collection' => 'Users',
            'collections' => 'User',
            'refTable' => 'user',
            'prop' => ["usrFirstName", "usrMiddleName"],
            'label' => 'Элемент',
//            'where' => ['department_id', null],
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
                'Действие' => ['add-item' => 'Добавить пользователей', 'edit' => 'Редактировать', 'delete' => 'Удалить']],
            'entity' => '\\Entity\\Department',
            'refEntity' => '\\Entity\\User',
            'Redirect' => 'izv/admin',
            'MessageError' => 'Ошибка',
            'add' => [
                'Action' => '/izv/admin/add/',
                'MessageError' => 'Ошибка параметров',
                'MessageSuccess' => 'Поле добавлено'
            ],
            'edit' => [
                'Action' => '/izv/admin/edit/',
                'MessageError' => 'Запись не найдена',
                'MessageSuccess' => 'Запись обновлена'
            ],
            'delete' => [
                'Action' => '/izv/admin/delete/',
                'MessageError' => 'Ошибка удаления записи',
                'MessageSuccess' => 'Запись удалена'
            ],
        ],
        'usrAction' => [
            'name' => 'izv/admin',
            'admin' => '/admin',
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
                'where' => 'a.category is null',
                'order' => 'a.id',
                'desc' => 'DESC',
            ],
            'css' => 'table table-striped table-hover',
            'ths' => [
                'Роль' => 'usrAction', 'Пользователь' => ['user','usrFirstName'],
                'Действие' => ['edit' => 'Редактировать', 'delete' => 'Удалить']],
            'entity' => '\\Entity\\UsrAction',
            'Redirect' => 'izv/admin',
            'MessageError' => 'Ошибка',
            'add' => [
                'Action' => '/izv/admin/add/',
                'MessageError' => 'Ошибка параметров',
                'MessageSuccess' => 'Поле добавлено'
            ],
            'edit' => [
                'Action' => '/izv/admin/edit/',
                'MessageError' => 'Запись не найдена',
                'MessageSuccess' => 'Запись обновлена'
            ],
            'delete' => [
                'Action' => '/izv/admin/delete/',
                'MessageError' => 'Ошибка удаления записи',
                'MessageSuccess' => 'Запись удалена'
            ],
        ],
        'templates' => [
            'name' => 'izv/admin',
            'admin' => '/admin',
            'table' => 'templates',
            'desc' => 'Шаблоны',
            'itemName' => 'Category',
            'collection' => 'Actions',
            'collections' => 'Action',
            'refTable' => 'usrAction',
            'prop' => ["UsrAction", ["User", "UsrFirstName"]],
            'label' => 'Элемент',
            'getUrl' => [
                'name' => '',
                'count' => '',
                'filterName' => '',
                'filterVal' => '',
            ],
            'where' => ['category', null],
            'query' => [
                'select' => 'a',
                'from' => 'Templates',
                'order' => 'a.id',
                'desc' => 'DESC',
            ],
            'css' => 'table table-striped table-hover',
            'ths' => [
                'Имя' => 'name',
                'Действие' => ['add-item' => 'Добавить','edit' => 'Редактировать','delete' => 'Удалить']],
            'entity' => '\\Entity\\Templates',
            'refEntity' => '\\Entity\\UsrAction',
            'Redirect' => '/izv/admin/add-item/',
            'MessageError' => 'Ошибка',
            'add' => [
                'Action' => '/izv/admin/add/',
                'MessageError' => 'Ошибка параметров',
                'MessageSuccess' => 'Поле добавлено'
            ],
            'edit' => [
                'Action' => '/izv/admin/edit/',
                'MessageError' => 'Запись не найдена',
                'MessageSuccess' => 'Запись обновлена'
            ],
            'delete' => [
                'Action' => '/izv/admin/delete/',
                'MessageError' => 'Ошибка удаления записи',
                'MessageSuccess' => 'Запись удалена'
            ],
            'add-item' => [
                'Action' => '/izv/admin/add-item/',
                'MessageError' => 'Ошибка добавления в коллекцию',
                'MessageSuccess' => 'Коллекция изменена'
            ],
        ],
        'notice' => [
            'name' => 'izv/all',
            'table' => 'notice',
            'desc' => 'Извещения',
            'getUrl' => [
                'name' => '',
                'count' => '',
                'filterName' => '',
                'filterVal' => '',
            ],
            'query' => [
                'select' => 'a',
                'from' => 'Izv',
                'order' => 'a.id',
                'desc' => 'DESC',
            ],
            'css' => 'table table-striped table-hover',
            'ths' => [
                'Номер извещения' => 'numberIzv',
                'Создал' => ['usrFirstName','usrFirstName'],
                'Действие' => ['show' => 'Посмотреть','edit' => 'Редактировать',
                    'delete' => 'Удалить', 'zip' => 'Скачать архив']],
            'entity' => '\\Entity\\Izv',
            'Redirect' => 'izv/all',
            'MessageError' => 'Ошибка',
            'add' => [
                'Action' => '/izv/all/add/',
                'css' => 'btn btn-success',
                'MessageError' => 'Ошибка параметров',
                'MessageSuccess' => 'Извещение добавлено'
            ],
            'edit' => [
                'Action' => '/izv/all/edit/',
                'MessageError' => 'Запись не найдена',
                'MessageSuccess' => 'Запись обновлена'
            ],
            'delete' => [
                'Action' => '/izv/all/delete/',
                'MessageError' => 'Ошибка удаления записи',
                'MessageSuccess' => 'Запись удалена'
            ],
        ],
    ]
    ];