<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 20.10.2017
 * Time: 11:13
 */

namespace Custom;


use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;

class BaseAdminController extends AbstractActionController
{
    protected $entityManager;
    protected $query;
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}

class BaseController extends BaseAdminController
{
    public function __construct($entityManager)
    {
        parent::__construct($entityManager);
    }
}