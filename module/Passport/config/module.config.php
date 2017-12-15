<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 13.12.2017
 * Time: 10:49
 */

use Passport\Controller\IndexController;
use Passport\Controller\AdminController;
use Passport\Factory\IndexControllerFactory;
use Passport\Factory\AdminControllerFactory;
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
            'passport' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/passport/',
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
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'Path' => __DIR__ . '/../../../public/content/passport/',
    'models' => [
        'passportAll' => [
            'name' => 'passport/all',
            'table' => 'passport',
            'desc' => 'Паспорта',
            'getUrl' => [
                'name' => '',
                'count' => '',
                'filterName' => '',
                'filterVal' => '',
            ],
            'query' => [
                'select' => 'a',
                'from' => 'Passport',
                'order' => 'a.status',
                'desc' => 'DESC',
            ],
            'css' => 'table table-striped table-hover',
            'ths' => [
                'Тип' => ['typeCurtain','alias'],'Название' => 'name',
                'Серия' => ['seria','number'],'Исполнение' => ['seria','performance'], 'ТУ' => ['tu','name'],
                'Дата' => 'date', 'Разработал' => ['creator','usrFirstName'],
                'Номер паспорта' => 'number', 'Статус' => ['status','status'],
                'Действие' => ['show' => 'PDF']],
            'tdClasses' => [
                'tac',null,'tac','tac','tac','tac','tac','tac','tac',null
            ],
            'thClasses' => [
                'tac','tac','tac','tac','tac','tac','tac','tac','tac','tac'
            ],
            'entity' => '\\Entity\\Passport',
            'Redirect' => 'passport/admin',
            'MessageError' => 'Ошибка',
            'add' => [
                'Action' => '/passport/admin/add/',
                'css' => 'btn btn-success',
                'MessageError' => 'Ошибка параметров',
                'MessageSuccess' => 'Поле добавлено'
            ],
            'edit' => [
                'Action' => '/passport/admin/edit/',
                'MessageError' => 'Запись не найдена',
                'MessageSuccess' => 'Запись обновлена'
            ],
            'delete' => [
                'Action' => '/passport/admin/delete/',
                'MessageError' => 'Ошибка удаления записи',
                'MessageSuccess' => 'Запись удалена'
            ],
        ],
        'passport' => [
            'name' => 'passport/admin',
            'admin' => '/admin',
            'table' => 'passport',
            'desc' => 'Паспорта',
            'getUrl' => [
                'name' => '',
                'count' => '',
                'filterName' => '',
                'filterVal' => '',
            ],
            'query' => [
                'select' => 'a',
                'from' => 'Passport',
                'order' => 'a.status',
                'desc' => 'DESC',
            ],
            'css' => 'table table-striped table-hover',
            'ths' => [
                'Тип' => ['typeCurtain','alias'], 'Название' => 'name',
                'Серия' => ['seria','number'],'Исполнение' => ['seria','performance'], 'ТУ' => ['tu','name'],
                'Дата' => 'date', 'Разработал' => ['creator','usrFirstName'],
                'Номер паспорта' => 'number', 'Статус' => ['status','status'],
                'Действие' => ['edit' => '<span class="glyphicon glyphicon-pencil"></span>',
                    'delete' => '<span class="glyphicon glyphicon-trash"></span>']],
            'tdClasses' => [
                'tac',null,'tac','tac','tac','tac','tac','tac','tac',null
            ],
            'thClasses' => [
                'tac','tac','tac','tac','tac','tac','tac','tac','tac','tac'
            ],
            'entity' => '\\Entity\\Passport',
            'Redirect' => 'passport/admin',
            'MessageError' => 'Ошибка',
            'add' => [
                'Action' => '/passport/admin/add/',
                'css' => 'btn btn-success',
                'MessageError' => 'Ошибка параметров',
                'MessageSuccess' => 'Поле добавлено'
            ],
            'edit' => [
                'Action' => '/passport/admin/edit/',
                'MessageError' => 'Запись не найдена',
                'MessageSuccess' => 'Запись обновлена'
            ],
            'delete' => [
                'Action' => '/passport/admin/delete/',
                'MessageError' => 'Ошибка удаления записи',
                'MessageSuccess' => 'Запись удалена'
            ],
        ],
        'seria' => [
            'name' => 'passport/admin',
            'admin' => '/admin',
            'table' => 'seria',
            'desc' => 'Серия',
            'getUrl' => [
                'name' => '',
                'count' => '',
                'filterName' => '',
                'filterVal' => '',
            ],
            'query' => [
                'select' => 'a',
                'from' => 'Seria',
                'order' => 'a.id',
                'desc' => 'DESC',
            ],
            'css' => 'table table-striped table-hover',
            'ths' => [
                'Номер серии' => 'number','Исполнение' => 'performance',
                'Действие' => ['edit' => 'Редактировать', 'delete' => 'Удалить']],
            'entity' => '\\Entity\\Seria',
            'Redirect' => 'passport/admin',
            'MessageError' => 'Ошибка',
            'add' => [
                'Action' => '/passport/admin/add/',
                'css' => 'btn btn-success',
                'MessageError' => 'Ошибка параметров',
                'MessageSuccess' => 'Поле добавлено'
            ],
            'edit' => [
                'Action' => '/passport/admin/edit/',
                'MessageError' => 'Запись не найдена',
                'MessageSuccess' => 'Запись обновлена'
            ],
            'delete' => [
                'Action' => '/passport/admin/delete/',
                'MessageError' => 'Ошибка удаления записи',
                'MessageSuccess' => 'Запись удалена'
            ],
        ],
        'typeCurtain' => [
            'name' => 'passport/admin',
            'admin' => '/admin',
            'table' => 'typeCurtain',
            'desc' => 'Источник тепла',
            'getUrl' => [
                'name' => '',
                'count' => '',
                'filterName' => '',
                'filterVal' => '',
            ],
            'query' => [
                'select' => 'a',
                'from' => 'TypeCurtain',
                'order' => 'a.id',
                'desc' => 'DESC',
            ],
            'css' => 'table table-striped table-hover',
            'ths' => [
                'Источник тепла' => 'name','Сокращение' => 'alias',
                'Действие' => ['edit' => 'Редактировать', 'delete' => 'Удалить']],
            'entity' => '\\Entity\\TypeCurtain',
            'Redirect' => 'passport/admin',
            'MessageError' => 'Ошибка',
            'add' => [
                'Action' => '/passport/admin/add/',
                'css' => 'btn btn-success',
                'MessageError' => 'Ошибка параметров',
                'MessageSuccess' => 'Поле добавлено'
            ],
            'edit' => [
                'Action' => '/passport/admin/edit/',
                'MessageError' => 'Запись не найдена',
                'MessageSuccess' => 'Запись обновлена'
            ],
            'delete' => [
                'Action' => '/passport/admin/delete/',
                'MessageError' => 'Ошибка удаления записи',
                'MessageSuccess' => 'Запись удалена'
            ],
        ],
        'tu' => [
            'name' => 'passport/admin',
            'admin' => '/admin',
            'table' => 'tu',
            'desc' => 'Технические условия',
            'getUrl' => [
                'name' => '',
                'count' => '',
                'filterName' => '',
                'filterVal' => '',
            ],
            'query' => [
                'select' => 'a',
                'from' => 'Tu',
                'order' => 'a.id',
                'desc' => 'DESC',
            ],
            'css' => 'table table-striped table-hover',
            'ths' => [
                'Номер' => 'number','Наименование' => 'name',
                'Описание' => ['description','trim' => 100], 'Код' => 'alias',
                'Действие' => ['edit' => 'Редактировать', 'delete' => 'Удалить']],
            'entity' => '\\Entity\\Tu',
            'Redirect' => 'passport/admin',
            'MessageError' => 'Ошибка',
            'add' => [
                'Action' => '/passport/admin/add/',
                'css' => 'btn btn-success',
                'MessageError' => 'Ошибка параметров',
                'MessageSuccess' => 'Поле добавлено'
            ],
            'edit' => [
                'Action' => '/passport/admin/edit/',
                'MessageError' => 'Запись не найдена',
                'MessageSuccess' => 'Запись обновлена'
            ],
            'delete' => [
                'Action' => '/passport/admin/delete/',
                'MessageError' => 'Ошибка удаления записи',
                'MessageSuccess' => 'Запись удалена'
            ],
        ],
    ],
];