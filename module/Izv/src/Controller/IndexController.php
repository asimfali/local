<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 10.11.2017
 * Time: 13:16
 */

namespace Izv\Controller;


use Custom\BaseAdminController;
use Entity\Templates;
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
        $admin = stripos($_SERVER['SCRIPT_NAME'],'admin');
        if (empty($this->model)) {
            foreach ($this->config as $k => $item) {
                if (is_array($item)) {
                 if (empty($admin) && !isset($item['admin']))
                {
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
        if (empty($c)) $c = 20;
        return $this->index($this->model, $c);
    }
    public function addAction()
    {
        $arr = $this->model;
        $this->set($arr, true, true);
        $this->form->setAction($arr['add']['Action']);
        $v = null;
        $this->form->setElemPar('date', 'Format', 'Y-m-d');
        $this->form->setElemPar('date', 'Value', date('Y-m-d'));
        return $this->showForm($arr);
    }
    public function showAction()
    {
        $pdf = null;
        $p = $this->params()->fromQuery()['pdf'];
        if (isset($p)) $pdf = true;
        $name = reset($this->config);
        $name = $name['name'];
        $name .= '/show';
        return $this->upload($this->path . 'izv/'. $p, $name, $pdf);
    }
    public function prAction()
    {
        $tmpl = $this->entityManager->getRepository(Templates::class);
        $kb = $tmpl->find(2);
        $foot = $this->toArray($kb, ['getActions','getUsrAction',['getUser', 'getUsrFirstName']]);
        $foot['addKey'] = 'Составил'; $foot['addValue'] = 'asdf';
        return ['head' => [], 'foot' => $foot];
    }

    public function downloadAction()
    {
        $tmpl = $this->entityManager->getRepository(Templates::class);
        $kb = $tmpl->find(2);
        $foot = $this->toArray($kb, ['getActions','getUsrAction',['getUser', 'getUsrFirstName']]);
        $foot['addKey'] = 'Составил'; $foot['addValue'] = 'asdf';
        return ['head' => [], 'foot' => $foot];
    }
}