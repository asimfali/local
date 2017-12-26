<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 10.11.2017
 * Time: 13:16
 */

namespace Izv\Controller;


use Custom\BaseAddController;
use Custom\Files;
use Custom\Izv;
use Custom\MyURL;
use Custom\DQuery;
use Entity\User;
use Zend\Form\Element;
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
        if (empty($this->model)) $this->model = 'notice';
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
        $count = 10;
        $arr = $this->model;
        if (empty($arr)) return ['default' => $this->names, 'base' => '', 'show' => $this->crurl(['izv', 'all' ,'show'])];
        $this->getReq();
        $q = new DQuery($this->entityManager, $arr['query']);
        $arr['getUrl']['name'] = $arr['table'];
        $arr['getUrl']['count'] = $count;
        $getUrls = new MyURL($arr['getUrl']);

        $page = $_GET['page'];
        if (empty($page)) $page = 1;
        $q->setPaginator($count, $page);
//        unset($arr['ths']['Действие']);
//        $arr['ths']['Действие'] = ['show' => 'Посмотреть', 'delete' => 'Удалить'];
        $this->getFm($this->plugin('flashMessenger'));
        $q->set(['name' => $arr['name'], 'class' => $arr['css'], 'ths' => $arr['ths'], 'table' => $arr['table']]);
        $p = ['fm' => $this->fm, 'route' => $arr['name'], 'q' => $q, 'name' => $q->ret(), 'table' => $getUrls->get(), 'desc' => $arr['desc'],
            'add' => $this->crurl([$arr['name'], 'add'], $getUrls->get())];
        return $this->baseView('view',['lside','izv'],['lside' =>['fsdf' => 'fsd'],'cont' => $p]);
    }
    public function addAction()
    {
        if (!isset($this->auth) || !$this->auth->hasIdentity()){
            return $this->redirect()->toRoute('auth-doctrine',['controller' => 'index', 'action' => 'login']);
        }
        $this->getReq();
        /**
         * @var User $user
         */
        $user = $this->auth->getIdentity();
        $izv = new Izv(new \Entity\Izv(),$user,$this->entityManager);

        $p = $this->params()->fromQuery('num'); $inc = false;
        if (empty($p)) {
            $p = $this->lastNumber('Izv', 'numberIzv');
            if (!isset($p)) $p = date('y') . '000';
            else $p = $p->getNumberIzv();
            $inc = true;
        } else {
            $p = $izv->izvNumber($p);
        }
        $p = $izv->numberIzv($p, $inc);
        $izv->init($this->path);
        $izv->upload($this->req);
        $arr = $this->model;
        $this->set($arr, true, true, ['NumberIzv' => $p, 'Appendix' => $izv->count(),
            'UsrFirstName' => $izv->user, 'Department' => $izv->dep]);
        $this->form->setAction($arr['add']['Action']);
        $v = null;
        $this->form->setElemPar('date', 'Format', 'Y-m-d');
        $this->form->setElemPar('date', 'Value', date('Y-m-d'));
        $pdf = null;
        if (!empty($izv->pdf)){
            $pdf = $izv->f->readIzv();
            if (!$pdf)
                $pdf = $izv->f->parseF('*.pdf');
        }
        $spl = explode('/',$izv->path);
        $l = count($spl);
        $p = '/izv/all/show/?path=' . $spl[$l-2] .'/'. $spl[$l-1] . '/';
        /**
         * @var Element $el
         */
//        $el = $this->form->getEl('numberIzv');
//        $num = $izv->izvNumber($el->getValue());
        return $this->showForm($arr, ['vals' => $pdf, 'path' => $p, 'user' => $user->getUsrFirstName()]);
//        return $this->baseView('view',['lside','izvAdd'],['lside' =>['fsdf' => 'fsd'],'cont' => $p]);
    }
    public function showAction()
    {
        $pdf = null;
        $p = $this->params()->fromQuery()['pdf'];
        $path = $this->params()->fromQuery()['path'];
        if (isset($p))
        {
            $pdf = true;
            $name = $this->config['notice'];
            $name = $name['name'];
            $name .= '/show';
            return $this->upload($this->path . $path . $p, $name, $pdf);
        }

        $this->getReq();
        $this->getId();
        /**
         * @var \Entity\Izv $izvEnt
         */
        $izvEnt = $this->entityManager->getRepository(\Entity\Izv::class)->find($this->id);
        $user = $izvEnt->getUsrFirstName();
        $p = $izvEnt->getNumberIzv();
        $arr = $this->model; $dir = '';
        $p = $this->numberIzv($p, $dir, false);
        $path = $this->path . '/izv/' . $dir;
        $this->upload($path);

        $this->set($arr, true, true, ['NumberIzv' => $p], $izvEnt);
        
        $this->form->setAction($arr['add']['Action']);
        $v = null;
        $this->form->setElemPar('date', 'Format', 'Y-m-d');
        $this->form->setElemPar('date', 'Value', date('Y-m-d'));
        $vals = $izvEnt->getIzvContents();
        $spl = explode('/',$path);
        $l = count($spl);
        $p = '/izv/all/show/?path=' . $spl[$l-2] .'/'. $spl[$l-1] . '/';
        return $this->showForm($arr, ['vals' => $vals, 'path' => $p, 'pdf' => '/izv/all/show/?path=izv/&pdf='.$dir.'.pdf', 'user' => $user->getUsrFirstName()]);
    }

//    public function editAction()
//    {
//        if (!isset($this->auth) || !$this->auth->hasIdentity()){
//            return $this->redirect()->toRoute('auth-doctrine',['controller' => 'index', 'action' => 'login']);
//        }
//        $this->getReq();
//        $this->getId();
//        /**
//         * @var User $user
//         */
//        $user = $this->auth->getIdentity();
//        /**
//         * @var \Entity\Izv $editIzv
//         */
//        $editIzv = $this->entityManager->getRepository(\Entity\Izv::class)->find($this->id);
//        if ($user != $editIzv->getUsrFirstName()) return null;
//        $p = $editIzv->getNumberIzv();
//        $arr = $this->model; $dir = '';
//        $p = $this->numberIzv($p, $dir, false);
//        $path = $this->path . '/izv/' . $dir;
//        $this->upload($path);
//
//        $this->set($arr, true, true, ['NumberIzv' => $p], $editIzv);
//
//        $this->form->setAction($arr['add']['Action']);
//        $v = null;
//        $this->form->setElemPar('date', 'Format', 'Y-m-d');
//        $this->form->setElemPar('date', 'Value', date('Y-m-d'));
//        $vals = $editIzv->getIzvContents();
//        $spl = explode('/',$path);
//        $l = count($spl);
//        $p = '/izv/all/show/?path=' . $spl[$l-2] .'/'. $spl[$l-1] . '/';
//        return $this->showForm($arr, ['vals' => $vals, 'path' => $p, 'user' => $user->getUsrFirstName()]);
//    }
    public function prAction()
    {
        /**
         * @var User $user
         */
        $user = $this->auth->getIdentity();
        $izv = new Izv(new \Entity\Izv(), $user, $this->entityManager);
        
        return $izv->pr($_POST['vals']);
    }

    public function downloadAction()
    {
        $user = $this->auth->getIdentity();
        $izv = new Izv(new \Entity\Izv(), $user, $this->entityManager);
        $par = $izv->pr($_POST);
        $par['resp'] = $this->response;
        $par['par']['name'] = $this->izvNumber($_POST['vals']['numberIzv']);
        if ($this->check('Izv',['numberIzv' => $par['par']['name']]))
        {
            echo json_encode(['error' => 'Номер извещения занят']);
            exit;
        }
        return $par;
    }
    public function saveAction()
    {
        /**
         * @var User $user
         */
        $user = $this->auth->getIdentity();
        $izv = new Izv(new \Entity\Izv(),$user,$this->entityManager);
        if($izv->save($_POST))
        {
            echo json_encode(['success' => 1, 'url' => '/izv/all/']);
            exit;
        }
        return null;
    }
    public function deleteAction()
    {
        if (!isset($this->auth) || !$this->auth->hasIdentity()){
            return $this->redirect()->toRoute('auth-doctrine',['controller' => 'index', 'action' => 'login']);
        }
        $this->getReq();
        if ($this->isIdentity('izv','UsrFirstName','Вы не можете удалить чужое извещение!!!'))
        {
            $num = $this->var->getNumberIzv();
            $f = new Files($this->path . '/izv/');
            $f->remove($num);
            $this->delete($this->model);
        } else {
            $this->set($this->model);
            return $this->redirect()->toRoute('izv/all');
        }
//        $this->getId();
//        /**
//         * @var User $user
//         */
//        $user = $this->auth->getIdentity();
//        /**
//         * @var \Entity\Izv $editIzv
//         */
//        $editIzv = $this->entityManager->getRepository(\Entity\Izv::class)->find($this->id);
//        if ($user != $editIzv->getUsrFirstName())
//        {
//            $this->set($this->model);
//            $this->fm->status = 'error';
//            $this->fm->add('Вы не можете удалить чужое извещение!!!');
//            return $this->redirect()->toRoute('izv/all');
////            return $this->redir([$this->model['Redirect']], $this->getUrls->get());
//        }
//        else {
//            $num = $editIzv->getNumberIzv();
//            $f = new Files($this->path . '/izv/');
//            $f->remove($num);
//            $this->delete($this->model);
//        }
        return null;
    }
    public function clearAction()
    {
        if (!isset($this->auth) || !$this->auth->hasIdentity()){
            return $this->redirect()->toRoute('auth-doctrine',['controller' => 'index', 'action' => 'login']);
        }
        $this->getReq();
        $num = $this->params()->fromQuery('num');
        if (empty($num)){
            return null;
        } else if ($this->check('Izv',['numberIzv' => $num])){
            echo json_encode(['error' => 'Номер извещиния занят']);
            exit;
        }
        $num = $this->izvNumber($num);
        $f = new Files($this->path . '/izv/');
        $f->remove($num);
        echo json_encode(['success' => 1]);
        exit;
    }
    public function zipAction()
    {
        $this->getReq();
        $this->getId();
        /**
         * @var \Entity\Izv $editIzv
         */
        $editIzv = $this->entityManager->getRepository(\Entity\Izv::class)->find($this->id);
        $numberIzv = $editIzv->getNumberIzv(); $m = null;
        $num = $this->numberIzv($numberIzv,$m,false);
        $f = new Files($this->path . '/izv/');
        $f->zip($numberIzv,$num);
    }
}