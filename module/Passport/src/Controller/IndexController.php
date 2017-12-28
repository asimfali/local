<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 13.12.2017
 * Time: 10:56
 */

namespace Passport\Controller;

use Custom\BaseAddController;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

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
        if (empty($this->model)) $this->model = 'passportAll';
        $this->model = $this->config[$this->model];
        $admin = stripos($_SERVER['SCRIPT_NAME'],'admin');
        if (empty($this->model)) {
            foreach ($this->config as $k => $item) {
                if (is_array($item) && $item['name'] == 'passport/admin') {
                    if (!empty($admin) && isset($item['admin'])) {
                        $this->names[$k][0] = $k;
                        $this->names[$k][1] = $item['desc'];
                    }
                }
            }
        }
        return parent::onDispatch($e);
    }

    public function indexAction()
    {
        $c = $this->params()->fromQuery('count');
        $post = $this->params()->fromQuery('post');
        $this->getReq();
        if (isset($post) && $this->req->isPost())
        {
            $where = $this->req->getPost()->toArray();
            $s = ['n' => 'CONCAT(s.number," ",s.performance)', 'act' => '=', 'type' => 'AND'];
            $date = ['t' => 'p', 'n' => 'date', 'act' => 'BETWEEN', 'type' => 'AND'];
            $in = ['t' => 'tc', 'n' => 'alias', 'act' => 'IN', 'type' => 'AND'];
            $name = ['t' => 'p', 'n' => 'name', 'act' => '=', 'type' => 'AND'];
            $status = ['t' => 'st', 'n' => 'status', 'act' => '=', 'type' => 'AND'];
            $cond = []; $vals = [];
            if (isset($where['seria'])) {$cond[] = $s; $vals[] = $where['seria'];}
            if (isset($where['start']) && isset($where['end'])){
                $cond[] = $date; $vals[] = $where['start']; $vals[] = $where['end'];
            }
            if (isset($where['EWA'])) {
                $in['count'] = count($where['EWA']);
                $cond[] = $in;
                foreach ($where['EWA'] as $item) {
                    $vals[] = $item;
                }
            }
            if (isset($where['status'])) {$cond[] = $status; $vals[] = $where['status'];}
            if (isset($where['type'])){
                $cond[] = $name; $vals[] = $where['type'];
            }
            $last = array_pop($cond);
            unset($last['type']);
            array_push($cond, $last);
            $this->setWhere($this->model,$cond,$vals);
        }
        if (empty($c)) $c = 20;
        $p = $this->indexPDO($this->model, $c, ['pdf','number']);
        $q = $p['q'];
        $s = $this->assembly($q->vals,'CONCAT(s.number," ",s.performance)');
        return $this->baseView('view',['passport/index/filter','passport'],
            ['lside' => ['status' => ['Действующий','Архивный'], 'series' => $s], 'cont' => $p]);
    }

    public function showAction()
    {
        $pdf = null;
        $p = $this->params()->fromQuery()['pdf'];
        $path = $this->params()->fromQuery()['path'];
        if (empty($path)) $path = '';
        if (isset($p))
        {
            $pdf = true;
            $name = reset($this->config);
            $name = $name['name'];
            $name .= '/show';
            return $this->upload($this->path . $path . $p, $name, $pdf);
        }
    }
}