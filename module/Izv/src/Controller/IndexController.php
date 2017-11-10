<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 10.11.2017
 * Time: 13:16
 */

namespace Izv\Controller;


use Custom\BaseAdminController;
use Custom\Query;

class IndexController extends BaseAdminController
{
    public function indexAction()
    {
        $q = new Query($this->entityManager, ['select' => 'a', 'from' => '', 'order' => '', 'desc' => '']);
        $q->setPaginator(10);
        return $q->ret('izv');
    }
    public function addAction()
    {
        
    }
    public function editAction()
    {

    }
    public function deleteAction()
    {

    }
}