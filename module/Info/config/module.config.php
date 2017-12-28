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
            'infoAdmin' => __DIR__ . '/../view/info/admin/index.phtml',
            'info' => __DIR__ . '/../view/info/index/index.phtml',
            'lside' => __DIR__ . '/../../Custom/left-sidebar.phtml',
            'view' => __DIR__ . '/../../Custom/view.phtml',
        ],
    ],
    'PathInfo' => __DIR__ . '/../../../public/content/info/',
    'models' => [
        'taAll' => [
            'name' => 'info/all',
            'table' => 'ta',
            'desc' => 'Теплообменники',
            'getUrl' => [
                'name' => 'taAll',
                'count' => '',
                'filterName' => '',
                'filterVal' => '',
            ],
            'query' => [
                'select' => 'a',
                'from' => 'Ta',
                'order' => ['a.status','a.seria','a.typeCurtain'],
                'desc' => ['DESC','DESC','DESC'],
            ],
            'css' => 'table table-striped table-hover',
//            'joins' => [
//                ['table' => 'user', 'alias' => 'u', 'pid' => 'creator', 'id' => 'id', 'act' => '='],
//                ['table' => 'tu', 'alias' => 'tu', 'pid' => 'tu', 'id' => 'id', 'act' => '='],
//                ['table' => 'seria', 'alias' => 's', 'pid' => 'seria', 'id' => 'id', 'act' => '='],
//                ['table' => 'status', 'alias' => 'st', 'pid' => 'status', 'id' => 'id', 'act' => '='],
//                ['table' => 'type_curtain', 'alias' => 'tc', 'pid' => 'typeCurtain', 'id' => 'id', 'act' => '='],
//            ],
//            'where' => [['table' => 'passport','name' => ['seria','typeCurtain'], 'act' => ' = ', 'val' => '?'],[8,5]],
            'sort' => [['p.name','ASC']],
//            'columns' => ['p.name','p.v','p.row','p.cu','p.stp','p.t','p.count','p.type','p.conf'],
            'ths' => [
                'Марка' => 'name', 'Длина<br>мм' => 'l', 'Ширина<br>мм' => 'w', 'Толщина<br>мм' => 'h',
                'Объем воды<br>л' => 'v', 'Число рядов<br>шт' => 'row',
                'Толщина меди<br>мм' => 'cu', 'Шаг ребер<br>мм' => 'stp', 'Толщина ребра<br>мм' => 't',
                'Число контруров<br>шт' => 'count', '&nbsp;<br>Тип коллектора' => 'type', '&nbsp;<br>Конфигурация' => 'conf',
                'Действие' => ['show' => 'PDF']],
            'tdClasses' => [
                'tac','tac','tac','tac','tac','tac','tac','tac','tac','tac','tac','tac'
            ],
            'thClasses' => [
                'tac','tac','tac','tac','tac','tac','tac','tac','tac','tac','tac','tac'
            ],
            'entity' => '\\Entity\\Ta',
            'Redirect' => 'info/all',
            'MessageError' => 'Ошибка',
            'add' => [
                'Action' => '/info/admin/add/',
                'css' => 'btn btn-success',
                'MessageError' => 'Ошибка параметров',
                'MessageSuccess' => 'Поле добавлено'
            ],
            'edit' => [
                'Action' => '/info/admin/edit/',
                'MessageError' => 'Запись не найдена',
                'MessageSuccess' => 'Запись обновлена'
            ],
            'delete' => [
                'Action' => '/info/admin/delete/',
                'MessageError' => 'Ошибка удаления записи',
                'MessageSuccess' => 'Запись удалена'
            ],
        ],
        'ta' => [
            'name' => 'info/admin',
            'admin' => '/admin',
            'table' => 'ta',
            'desc' => 'Теплообменники',
            'getUrl' => [
                'name' => '',
                'count' => '',
                'filterName' => '',
                'filterVal' => '',
            ],
            'query' => [
                'select' => 'a',
                'from' => 'Ta',
                'order' => 'a.name',
                'desc' => 'DESC',
            ],
            'css' => 'table table-striped table-hover',
            'ths' => [
                'Марка<br>' => 'name', 'Длина<br>мм' => 'l', 'Ширина<br>мм' => 'w', 'Толщина<br>мм' => 'h',
                'Объем воды<br>л' => 'v', 'Число рядов<br>шт' => 'row',
                'Толщина меди<br>мм' => 'cu', 'Шаг ребер<br>мм' => 'stp', 'Толщина ребра<br>мм' => 't',
                'Число контруров<br>шт' => 'count', '&nbsp;<br>Тип коллектора' => 'type', '&nbsp;<br>Конфигурация' => 'conf',
                'Действие' => ['edit' => '<span class="glyphicon glyphicon-pencil"></span>',
                    'delete' => '<span class="glyphicon glyphicon-trash"></span>']],
            'tdClasses' => [
                'tac','tac','tac','tac','tac','tac','tac','tac','tac','tac','tac','tac'
            ],
            'defVals' => [
                "ТЕРМА", null, null, null, null, null, 0.35, 2.5, 0.15, null, null, "S22-10", "Сохранить"
            ],
            'thClasses' => [
                'tac','tac','tac','tac','tac','tac','tac','tac','tac','tac'
            ],
            'entity' => '\\Entity\\Ta',
            'Redirect' => 'info/admin',
            'MessageError' => 'Ошибка',
            'add' => [
                'Action' => '/info/admin/add/',
                'css' => 'btn btn-success',
                'MessageError' => 'Ошибка параметров',
                'MessageSuccess' => 'Поле добавлено'
            ],
            'edit' => [
                'Action' => '/info/admin/edit/',
                'MessageError' => 'Запись не найдена',
                'MessageSuccess' => 'Запись обновлена'
            ],
            'delete' => [
                'Action' => '/info/admin/delete/',
                'MessageError' => 'Ошибка удаления записи',
                'MessageSuccess' => 'Запись удалена'
            ],
        ],
    ],
];
