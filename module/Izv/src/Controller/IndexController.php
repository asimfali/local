<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 10.11.2017
 * Time: 13:16
 */

namespace Izv\Controller;


use Custom\BaseAddController;
use Entity\Templates;
use Entity\User;
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
        if (!isset($this->auth) || !$this->auth->hasIdentity()){
            return $this->redirect()->toRoute('auth-doctrine',['controller' => 'index', 'action' => 'login']);
        }
        /**
         * @var User $user
         */
        $user = $this->auth->getIdentity();
        $dep = $user->getUsrDepartment();
        $alias = $dep->getAlias();
        $tmpl = $this->entityManager->getRepository(Templates::class);
        $tmpl = $tmpl->findOneBy(['name' => $alias]);
        $p = $this->lastNumber('Izv','numberIzv');
        if (!isset($p)) $p = 1;
        $arr = $this->model;
        $p = $this->numberIzv($p, date('y'));
        $this->set($arr, true, true, ['NumberIzv' => $p, 'Appendix' => $this->PDF_DXF(2,2),
            'UsrFirstName' => $user, 'Department' => $dep]);
        $this->form->setAction($arr['add']['Action']);
        $v = null;
        $this->form->setElemPar('date', 'Format', 'Y-m-d');
        $this->form->setElemPar('date', 'Value', date('Y-m-d'));
        return $this->showForm($arr, ['vals' => [1,1], 'user' => $user->getUsrFirstName()]);
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