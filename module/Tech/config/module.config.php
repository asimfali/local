<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 25.12.2017
 * Time: 11:37
 */
use Tech\Factory\IndexControllerFactory;
use Tech\Factory\AdminControllerFactory;
use Tech\Controller\IndexController;
use Tech\Controller\AdminController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'controllers' =>[
        'factories' => [
            IndexController::class => IndexControllerFactory::class,
            AdminController::class => AdminControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'tech' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/tech/',
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
            __DIR__ . '/../../Views',
        ],
        'template_map' => [
            'techAdmin' => __DIR__ . '/../view/tech/admin/index.phtml',
            'tech' => __DIR__ . '/../view/tech/index/index.phtml',
            'lside' => __DIR__ . '/../../Custom/left-sidebar.phtml',
            'view' => __DIR__ . '/../../Custom/view.phtml',
        ],
    ],
    'PathTech' => __DIR__ . '/../../../public/content/tech/',
    'models' => [
        'defectAll' => [
            'name' => 'tech/all',
            'table' => 'defect',
            'desc' => 'Дефекты',
            'getUrl' => [
                'name' => 'defectAll',
                'count' => '',
                'filterName' => '',
                'filterVal' => '',
            ],
            'css' => 'table table-striped table-hover',
            'joins' => [
                ['table' => 'action', 'alias' => 'a', 'pid' => 'action', 'id' => 'id', 'act' => '='],
                ['table' => 'listdef', 'alias' => 'l', 'pid' => 'description', 'id' => 'id', 'act' => '='],
            ],
            'sort' => [['p.id','DESC']],
            'ths' => [
                '№' => 'p.number', 'Дата' => 'p.date', 'Чертеж' => 'p.drawing', 'Тип' => 'p.type',
                'Кол-во' => ['p.count',0], 'Описание' => ['l.name',0],
                'Заключение' => ['a.name',1],
                ],
            'tdClasses' => [
                'tac','tac','tac','tac','tac','tac','tac','tac','tac','tac','tac','tac'
            ],
            'thClasses' => [
                'tac','tac','tac','tac','tac','tac','tac','tac','tac','tac','tac','tac'
            ],
            'entity' => '\\Entity\\Defect',
            'Redirect' => 'tech/all',
            'MessageError' => 'Ошибка',
        ],
        'defect' => [
            'name' => 'tech/admin',
            'admin' => '/admin',
            'table' => 'defect',
            'desc' => 'Дефекты',
            'getUrl' => [
                'name' => '',
                'count' => '',
                'filterName' => '',
                'filterVal' => '',
            ],
            'query' => [
                'select' => 'a',
                'from' => 'Defect',
                'order' => 'a.id',
                'desc' => 'DESC',
            ],
            'css' => 'table table-striped table-hover',
            'ths' => [
                '№' => 'number', 'Дата' => 'date', 'Чертеж' => 'drawing', 'Тип' => 'type',
                'Кол-во' => 'count', 'Описание' => ['description','name'], 'Заключение' => ['action','name'],
                'Действие' => ['edit' => 'Редактировать',
                    'delete' => 'Удалить']],
            'tdClasses' => [
                'tac','tac','tac','tac','tac','tac','tac','tac','tac','tac','tac','tac'
            ],
//            'defVals' => [
//                "ТЕРМА", null, null, null, null, null, 0.35, 2.5, 0.15, null, null, "S22-10", "Сохранить"
//            ],
            'thClasses' => [
                'tac','tac','tac','tac','tac','tac','tac','tac','tac','tac'
            ],
            'entity' => '\\Entity\\Defect',
            'Redirect' => 'tech/admin',
            'MessageError' => 'Ошибка',
            'add' => [
                'Action' => '/tech/admin/add/',
                'css' => 'btn btn-success',
                'MessageError' => 'Ошибка параметров',
                'MessageSuccess' => 'Поле добавлено'
            ],
            'edit' => [
                'Action' => '/tech/admin/edit/',
                'MessageError' => 'Запись не найдена',
                'MessageSuccess' => 'Запись обновлена'
            ],
            'delete' => [
                'Action' => '/tech/admin/delete/',
                'MessageError' => 'Ошибка удаления записи',
                'MessageSuccess' => 'Запись удалена'
            ],
        ],
        'action' => [
            'name' => 'tech/admin',
            'admin' => '/admin',
            'table' => 'action',
            'desc' => 'Действия',
            'getUrl' => [
                'name' => '',
                'count' => '',
                'filterName' => '',
                'filterVal' => '',
            ],
            'query' => [
                'select' => 'a',
                'from' => 'Action',
                'order' => 'a.id',
                'desc' => 'DESC',
            ],
            'css' => 'table table-striped table-hover',
            'ths' => [
                'Заключение' => 'name',
                'Действие' => ['edit' => 'Редактировать',
                    'delete' => 'Удалить']],
            'tdClasses' => [
                'tac','tac','tac','tac','tac','tac','tac','tac','tac','tac','tac','tac'
            ],
//            'defVals' => [
//                "ТЕРМА", null, null, null, null, null, 0.35, 2.5, 0.15, null, null, "S22-10", "Сохранить"
//            ],
            'thClasses' => [
                'tac','tac','tac','tac','tac','tac','tac','tac','tac','tac'
            ],
            'entity' => '\\Entity\\Action',
            'Redirect' => 'tech/admin',
            'MessageError' => 'Ошибка',
            'add' => [
                'Action' => '/tech/admin/add/',
                'css' => 'btn btn-success',
                'MessageError' => 'Ошибка параметров',
                'MessageSuccess' => 'Поле добавлено'
            ],
            'edit' => [
                'Action' => '/tech/admin/edit/',
                'MessageError' => 'Запись не найдена',
                'MessageSuccess' => 'Запись обновлена'
            ],
            'delete' => [
                'Action' => '/tech/admin/delete/',
                'MessageError' => 'Ошибка удаления записи',
                'MessageSuccess' => 'Запись удалена'
            ],
        ],
        'listdef' => [
            'name' => 'tech/admin',
            'admin' => '/admin',
            'table' => 'listdef',
            'desc' => 'Список дефектов',
            'getUrl' => [
                'name' => '',
                'count' => '',
                'filterName' => '',
                'filterVal' => '',
            ],
            'query' => [
                'select' => 'a',
                'from' => 'Listdef',
                'order' => 'a.category',
                'desc' => 'DESC',
            ],
            'css' => 'table table-striped table-hover',
            'ths' => [
                'Категория' => 'category', 'Описание' => 'name',
                'Действие' => ['edit' => 'Редактировать',
                    'delete' => 'Удалить']],
            'tdClasses' => [
                'tac','tac','tac','tac','tac','tac','tac','tac','tac','tac','tac','tac'
            ],
//            'defVals' => [
//                "ТЕРМА", null, null, null, null, null, 0.35, 2.5, 0.15, null, null, "S22-10", "Сохранить"
//            ],
            'thClasses' => [
                'tac','tac','tac','tac','tac','tac','tac','tac','tac','tac'
            ],
            'entity' => '\\Entity\\Listdef',
            'Redirect' => 'tech/admin',
            'MessageError' => 'Ошибка',
            'add' => [
                'Action' => '/tech/admin/add/',
                'css' => 'btn btn-success',
                'MessageError' => 'Ошибка параметров',
                'MessageSuccess' => 'Поле добавлено'
            ],
            'edit' => [
                'Action' => '/tech/admin/edit/',
                'MessageError' => 'Запись не найдена',
                'MessageSuccess' => 'Запись обновлена'
            ],
            'delete' => [
                'Action' => '/tech/admin/delete/',
                'MessageError' => 'Ошибка удаления записи',
                'MessageSuccess' => 'Запись удалена'
            ],
        ],
    ],
];