<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 10.11.2017
 * Time: 13:30
 */

namespace Custom;


use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManager;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

class Query
{
    /**
     * @var EntityManager
     */
    protected $em;
    /**
     * @var QueryBuilder
     */
    protected $query;
    /**
     * @var Paginator
     */
    protected $p;

    /**
     * Query constructor.
     * @param EntityManager $entityManager
     * @param $arr - array
     */
    public function __construct(EntityManager $entityManager, $arr)
    {
        $this->em = $entityManager;
        $this->query = $this->em->createQueryBuilder();
        $this->query
            ->select($arr['select'])
            ->from('Entity\\'. $arr['from'], $arr['select'])
            ->orderBy($arr['order'],$arr['desc']);
        $a = new DoctrinePaginator(new ORMPaginator($this->query,false));
        $this->p = new Paginator($a);

//        $p->setCurrentPageNumber((int) $this->params()->fromQuery('page',1));
    }
    public function setPaginator($count)
    {
        $this->p->setDefaultItemCountPerPage($count);
    }
    public function ret($name)
    {
        return $this->p;
    }
}