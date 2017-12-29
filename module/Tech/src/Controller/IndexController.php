<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 25.12.2017
 * Time: 11:38
 */

namespace Tech\Controller;


use Custom\BaseAddController;
use Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\MvcEvent;

class IndexController extends BaseAddController
{
    public function __construct(EntityManager $entityManager, AuthenticationService $authenticationService, $config)
    {
        $this->config = $config;
        $this->path = $this->config['Path'];
        parent::__construct($entityManager, $authenticationService);
    }

    public function onDispatch(MvcEvent $e)
    {
        $this->model = $this->findModel();
//        if (empty($this->model)) $this->model = 'passportAll';
        $this->model = $this->config[$this->model];
        $admin = stripos($_SERVER['SCRIPT_NAME'],'admin');
//        if (empty($this->model)) {
        foreach ($this->config as $k => $item) {
            if (is_array($item) && $item['name'] == 'tech/admin') {
                if (!empty($admin) && isset($item['admin'])) {
                    $this->names[$k][0] = $k;
                    $this->names[$k][1] = $item['desc'];
                }
            } else if (is_array($item) && $item['name'] == 'tech/all'){
                $this->names[$k][0] = $k;
                $this->names[$k][1] = $item['desc'];
            }
//            }
        }
        return parent::onDispatch($e);
    }

    public function indexAction()
    {
        $p = $this->indexPDO($this->model, 45);
        return $this->baseView('view',['index/lside','tech/index/index'],['lside'=>[],'cont' => $p]);
    }
    public function prAction()
    {
        $this->getReq();
        if ($this->req->isPost()){
            $p = $this->req->getPost();
            $start = $p->get('start');
            $end = $p->get('end');
            $this->model['where'] = ['cond' => ['table' => 'p','name' => 'date', 'act' => ' BETWEEN ', 'val' => '?,?'],
                'vals' => [$start,$end]];
        }
        $c = 45;
        return $this->forPrint($this->model,'',$c);
    }
}