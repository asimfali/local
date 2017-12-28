<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 22.11.2017
 * Time: 12:19
 */

namespace Izv\Controller;


use Custom\BaseController;
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
    public function __construct($entityManager, $authenticationService, $config)
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
                if (is_array($item) && $item['name'] == 'izv/admin') {
                    if (!empty($admin) && isset($item['admin'])) {
                        $this->names[$k][0] = $k;
                        $this->names[$k][1] = $item['desc'];
                    } 
                }
            }
//        }
        return parent::onDispatch($e);
    }

    public function indexAction()
    {
        $c = $this->params()->fromQuery('count');
        if (empty($c)) $c = 20;
        $p = $this->index($this->model, $c);
        return $this->baseView('view',['admin/lside','izv/admin/index'],['lside' => ['list' => $this->names, 'base' => ''],'cont' => $p]);
    }
    public function addAction()
    {
        $p = $this->add($this->model);
        return $this->baseView('view',['admin/lside','izv/admin/add'],['lside' => ['list' => $this->names, 'base' => ''],'cont' => $p]);
    }
    public function addItemAction()
    {
        $p = $this->itemAdd($this->model, $this->config[$this->model['refTable']]);
        return $this->baseView('view',['admin/lside','izv/admin/add-item'],['lside' => ['list' => $this->names, 'base' => ''],'cont' => $p]);
    }
    public function deleteItemAction()
    {
        $id = $this->params()->fromQuery('id');
        $pname = $this->params()->fromQuery('pname');
        return $this->deleteItem($this->config[$pname], $id);
    }
    public function collectionAction()
    {

    }
    public function editAction()
    {
        $p = $this->edit($this->model);
        return $this->baseView('view',['admin/lside','izv/admin/edit'],['lside' => ['list' => $this->names, 'base' => ''],'cont' => $p]);
    }
    public function deleteAction()
    {
        return $this->delete($this->model);
    }
    public function showAction()
    {
        $pdf = null;
        $p = $this->params()->fromQuery()['pdf'];
        if (isset($p)) $pdf = true;
        $name = reset($this->config);
        $name = $name['name'];
        $name .= '/show';
        return $this->upload($this->path . 'izv/' . $p, $name, $pdf);
    }
}