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
use Custom\Query;
use Entity\IzvContent;
use Entity\Templates;
use Entity\User;
use Zend\Http\Request;
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
        $q = new Query($this->entityManager, $arr['query']);
        $arr['getUrl']['name'] = $arr['table'];
        $arr['getUrl']['count'] = $count;
        $getUrls = new MyURL($arr['getUrl']);

        $page = $_GET['page'];
        if (empty($page)) $page = 1;
        $q->setPaginator($count, $page);
        unset($arr['ths']['Действие']);
        $arr['ths']['Действие'] = ['show' => 'Посмотреть', 'delete' => 'Удалить'];
        $this->getFm($this->plugin('flashMessenger'));
        $q->set(['name' => $arr['name'], 'class' => $arr['css'], 'ths' => $arr['ths'], 'table' => $arr['table']]);
        return ['fm' => $this->fm, 'route' => $arr['name'], 'q' => $q, 'name' => $q->ret(), 'table' => $getUrls->get(), 'desc' => $arr['desc'],
            'add' => $this->crurl([$arr['name'], 'add'], $getUrls->get())];
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
        $izv->init();
        $p = $this->lastNumber('Izv','numberIzv');
        if (!isset($p)) $p = date('y').'000';
        else $p = $p->getNumberIzv();
        $arr = $this->model; $dir = '';
        $p = $this->numberIzv($p, $dir);
        $path = $this->path . '/izv/' . $dir;
        $this->upload($path);
        $f = new Files($path);
        $pdf = $f->countF('*.pdf');
        $dxf = $f->countF('*.dxf');
        $this->set($arr, true, true, ['NumberIzv' => $p, 'Appendix' => $this->PDF_DXF($pdf,$dxf),
            'UsrFirstName' => $user, 'Department' => $izv->dep]);
        $this->form->setAction($arr['add']['Action']);
        $v = null;
        $this->form->setElemPar('date', 'Format', 'Y-m-d');
        $this->form->setElemPar('date', 'Value', date('Y-m-d'));
        if (!empty($pdf)){
            $pdf = $f->parseF('*.pdf');
        } else $pdf = [1];
        $spl = explode('/',$path);
        $l = count($spl);
        $p = '/izv/all/show/?path=' . $spl[$l-2] .'/'. $spl[$l-1] . '/';
        return $this->showForm($arr, ['vals' => $pdf, 'path' => $p, 'user' => $user->getUsrFirstName()]);
    }
    public function showAction()
    {
        $pdf = null;
        $p = $this->params()->fromQuery()['pdf'];
        $path = $this->params()->fromQuery()['path'];
        if (isset($p))
        {
            $pdf = true;
            $name = reset($this->config);
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
        $par['par']['numberIzv'] = $this->izvNumber($par['par']['numberIzv']);
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
        $this->getId();
        /**
         * @var User $user
         */
        $user = $this->auth->getIdentity();
        /**
         * @var \Entity\Izv $editIzv
         */
        $editIzv = $this->entityManager->getRepository(\Entity\Izv::class)->find($this->id);
        if ($user != $editIzv->getUsrFirstName()) return null;

        $this->delete($this->model);
    }
}