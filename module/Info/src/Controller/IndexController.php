<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 13.12.2017
 * Time: 13:52
 */

namespace Info\Controller;


use Custom\BaseAddController;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\MvcEvent;

class IndexController extends BaseAddController
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
    public function __construct(EntityManager $entityManager, $authenticationService, $config)
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
                if (is_array($item) && $item['name'] == 'info/admin') {
                    if (!empty($admin) && isset($item['admin'])) {
                        $this->names[$k][0] = $k;
                        $this->names[$k][1] = $item['desc'];
                    }
                } else if (is_array($item) && $item['name'] == 'info/all'){
                    $this->names[$k][0] = $k;
                    $this->names[$k][1] = $item['desc'];
                }
//            }
        }
        return parent::onDispatch($e);
    }
    public function indexAction()
    {
        $c = $this->params()->fromQuery('count');
        if (empty($c)) $c = 50;
        $p = $this->indexPDO($this->model, $c);
        return $this->baseView('view',['lside','info'],['lside'=>['list' => $this->names,'base' => ''],'cont' => $p]);
    }
}