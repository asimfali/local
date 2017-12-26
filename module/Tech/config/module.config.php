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
        'testAll' => [
            'name' => 'tech/all',
            'table' => 'ta',
            'desc' => 'Тест',
            'getUrl' => [
                'name' => '',
                'count' => '',
                'filterName' => '',
                'filterVal' => '',
            ],
            'css' => 'table table-striped table-hover',
            'sort' => [['p.name','DESC']],
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
            'Redirect' => 'tech/all',
            'MessageError' => 'Ошибка',
        ],
        'test' => [
            'name' => 'tech/admin',
            'admin' => '/admin',
            'table' => 'ta',
            'desc' => 'Тест',
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