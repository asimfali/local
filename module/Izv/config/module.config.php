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
//                'may_terminate' => true,
//                'child_routes' => [
//                    'query' => [
//                        'type' => 'Query',
//                    ],
//                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'Path' => '\\\\obmen\\kb\\Д А Н И Л А\\И З В Е Щ Е Н И Я\\',
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
                '#' => 'id','Отдел' => 'name', 'Псевдоним' => 'alias',
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
    ]
    ];