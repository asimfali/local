<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 25.12.2017
 * Time: 11:38
 */

namespace Tech\Controller;

use Custom\BaseController;
use Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\MvcEvent;

class AdminController extends BaseController
{
    /**
     * @var array $config
     */
    public $config;
    /**
     * @var $model
     */
    protected $model;
    /**
     * @var $path
     */
    protected $path;
    public function __construct(EntityManager $entityManager, AuthenticationService $authenticationService, $config)
    {
        $this->config = $config;
        $this->path = $this->config['Path'];
        parent::__construct($entityManager, $authenticationService);
    }
    public function onDispatch(MvcEvent $e)
    {
        $this->model = $this->findModel();
        $this->model = $this->config[$this->model];
        $admin = stripos($_SERVER['SCRIPT_NAME'],'admin');
//        if (empty($this->model)) {
            foreach ($this->config as $k => $item) {
                if (is_array($item) && $item['name'] == 'tech/admin') {
                    if (!empty($admin) && isset($item['admin'])) {
                        $this->names[$k][0] = $k;
                        $this->names[$k][1] = $item['desc'];
                    }
                }
//            }
        }
        return parent::onDispatch($e);
    }
    public function indexAction()
    {
        $c = $this->params()->fromQuery('count');
        if (empty($c)) $c = 20;
        $p = $this->index($this->model, $c);
        return $this->baseView('view',['admin/lside','tech/admin/index'],['lside'=>['list' => $this->names,'base' => ''],'cont' => $p]);
    }
    
    public function editAction()
    {
        $p = $this->edit($this->model);
        return $this->baseView('view',['admin/lside','tech/admin/edit'],['lside'=>['list' => $this->names,'base' => ''],'cont' => $p]);
    }

    public function deleteAction()
    {
        return $this->delete($this->model);
    }

    public function addAction()
    {
        $p = $this->add($this->model);
        return $this->baseView('view',['admin/lside','tech/admin/add'],['lside'=>['list' => $this->names,'base' => ''],'cont' => $p]);
    }
}