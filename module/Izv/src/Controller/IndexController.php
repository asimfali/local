<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 10.11.2017
 * Time: 13:16
 */

namespace Izv\Controller;


use Custom\BaseAdminController;
use Zend\Mvc\MvcEvent;

class IndexController extends BaseAdminController
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
    public function __construct($entityManager, $config)
    {
        $this->config = $config;
        $this->path = $this->config['Path'];
        parent::__construct($entityManager);
    }
    public function onDispatch(MvcEvent $e)
    {
        $this->model = $this->findModel();
        $this->model = $this->config[$this->model];
        if (empty($this->model)) {
            foreach ($this->config as $k => $item) {
                if (is_array($item)) {
                    $this->names[$k][0] = $k;
                    $this->names[$k][1] = $item['desc'];
                }
            }
        }
        return parent::onDispatch($e); // TODO: Change the autogenerated stub
    }

    public function indexAction()
    {
        return [];
    }
    public function showAction()
    {
        $p = $this->params()->fromQuery()['pdf'];
        if (isset($p)) $pdf = true;
        return $this->upload($this->path . 'izv/' . $p, $pdf);
    }
    
}