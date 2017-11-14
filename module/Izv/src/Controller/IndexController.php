<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 10.11.2017
 * Time: 13:16
 */

namespace Izv\Controller;


use Custom\BaseAdminController;
use Custom\MyForm;
use Custom\Query;
use Entity\Fields;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;

class IndexController extends BaseAdminController
{
    public function indexAction()
    {
        $q = new Query($this->entityManager, ['select' => 'a', 'from' => 'Fields', 'order' => 'a.id', 'desc' => 'DESC']);
        $q->setPaginator(10);
        $this->getFm($this->flashMessenger());
        return ['izv' => $q->ret('izv'), 'em' => $this->entityManager, 'fm' => $this->fm,
            'ths' => ['#','Категория','Текст','Опубликовано','Действие']];
    }
    public function addAction()
    {
        $this->getFm($this->flashMessenger());
        return $this->add(['Entity' => '\\Entity\\Fields', 'Action' => '/izv/add/', 'Redirect' => 'izv',
            'MessageError' => 'Ошибка параметров', 'MessageSuccess' => 'Поле добавлено']);
    }
    public function editAction()
    {
        $this->getFm($this->flashMessenger());
        return $this->edit(['Entity' => '\\Entity\\Fields', 'Action' => '/izv/edit/', 'Redirect' => 'izv',
            'MessageError' => 'Запись не найдена', 'MessageSuccess' => 'Запись обновлена']);
    }
    public function deleteAction()
    {
        $this->getFm($this->flashMessenger());
        return $this->edit(['Entity' => '\\Entity\\Fields', 'Action' => '/izv/delete/', 'Redirect' => 'izv',
            'MessageError' => 'Ошибка удаления записи', 'MessageSuccess' => 'Запись удалена']);
    }
}