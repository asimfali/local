<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 20.10.2017
 * Time: 11:13
 */

namespace Custom;


use Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;

class BaseAdminController extends AbstractActionController
{
    /**
     * @var EntityManager
     */
    protected $entityManager;
    /**
     * @var FlashMsgr
     */
    protected $fm;
    /**
     * @var
     */
    protected $id;
    /**
     * @var Request
     */
    protected $req;
    /**
     * @var MyForm
     */
    public $form;
    /**
     * @var array $names
     */
    protected $names;
    /**
     * @var integer $count
     */
    protected $count;
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function getFm(FlashMessenger $flashMessenger){
        $this->fm = new FlashMsgr($flashMessenger);
    }
    public function addMsg($message)
    {
        $this->fm->add($message);
    }
    public function getId()
    {
        return $this->id = $this->params()->fromRoute('id',0);
    }
    public function redir($path, $get)
    {
        if (empty($get))
            return $this->redirect()->toRoute($path);
        else {
            $url = $this->crurl([$path], $get);
            return $this->redirect()->toUrl($url);
        }

    }
    public function getReq()
    {
        return $this->req = $this->getRequest();
    }
    public function error($message)
    {
        $this->fm->status = 'error';
        $msg = $message;
        foreach ($this->form->getForm()->getInputFilter()->getInvalidInput() as $errors) {
            foreach ($errors->getMessages() as $error){
                $msg .= ' ' . $error;
            }
        }
        $this->fm->add($msg);
    }
    public function getItem($arr)
    {
        $id = $this->getId();
        $r = $this->entityManager->getRepository($arr['entity']);
        $item = $r->find($id);
        if(empty($item)){
            $this->fm->status = 'error';
            $this->fm->add($arr['MessageError']);
            $this->redir($arr['Redirect']);
            return null;
        }
        return $item;
    }
    public function findModel(){
        $get = $this->params()->fromQuery();
        if (!empty($get)){
            return $get['name'];
        }
        $get = $this->params()->fromPost();
        if (!empty($get)){
            return $get['hidden'];
        }
        return null;
    }
    public function crurl($arr, $name){
        $url = '/';
        foreach ($arr as $item) {
            $url .= $item . '/';
        }
        if (!empty($name)){
            $url .= $name;
        }
        return $url;
    }
    public function getPublic(){
        
    }
    public function setParam(&$arr, $names){
        foreach ($names as $name) {
            $c = $this->params()->fromQuery($name);
            if (isset($c)) $arr[$name] = $c;
        }
    }
    public function index($arr, $count = 10)
    {
        if (empty($arr)) return ['default' => $this->names, 'show' => $this->crurl(['izv', 'show'])];
        $this->getReq();
        $q = new Query($this->entityManager, $arr['query']);
        $arr['getUrl']['name'] = $arr['table'];
        $arr['getUrl']['count'] = $count;
        $getUrls = new MyURL($arr['getUrl']);

        $page = $_GET['page'];
        if (empty($page)) $page = 1;
        $q->setPaginator($count, $page);

        $this->getFm($this->flashMessenger());
        $q->set(['name' => $arr['name'], 'class' => $arr['css'], 'ths' => $arr['ths'], 'table' => $arr['table']]);
        return ['fm' => $this->fm, 'q' => $q, 'name' => $q->ret(), 'table' => $getUrls->get(), 'desc' => $arr['desc'],
            'add' => $this->crurl([$arr['name'], 'add'], $getUrls->get())];
    }
    public function add($arr)
    {
        $this->getFm($this->flashMessenger());
        $arr['getUrl']['name'] = $arr['table'];
        $this->setParam($arr['getUrl'],['count']);
        $getUrls = new MyURL($arr['getUrl']);

        $fields = new $arr['entity']();
        $this->form = new MyForm($fields, $this->entityManager);
        $this->form->setValue('hidden', 'value', $arr['table']);
        $this->getReq();
        $this->form->setAction($arr['add']['Action']);
        if ($this->req->isPost()){
            $this->form->setData($this->req->getPost());
            if ($this->form->getForm()->isValid()){
                $this->entityManager->persist($fields);
                $this->entityManager->flush();
                $this->fm->add($arr['add']['MessageSuccess']);
            } else {
                $this->error($arr['add']['MessageError']);
            }
        } else {
            $this->form->getForm()->prepare();
            return ['form' => $this->form, 'id' => $this->id, 'add' => $this->crurl([$arr['name'], 'add'], $getUrls->get())];
        }
        $this->redir($arr['Redirect'],  $getUrls->get());
        return null;
    }
    public function edit($arr)
    {
        $this->getFm($this->flashMessenger());
        $arr['getUrl']['name'] = $arr['table'];
        $this->setParam($arr['getUrl'],['count']);
        $getUrls = new MyURL($arr['getUrl']);

        $fields = new $arr['entity']();
        $item = $this->getItem($arr);
        $this->form = new MyForm($fields, $this->entityManager);
        $this->form->setValue('hidden', 'value', $arr['table']);
        $this->form->getForm()->bind($item);
        $this->req = $this->getReq();
        if($this->req->isPost()){
            $this->form->setData($this->req->getPost());
            if($this->form->getForm()->isValid()){
                $this->entityManager->persist($item);
                $this->entityManager->flush();
                $this->fm->add($arr['edit']['MessageSuccess']);
            } else {
                $this->error($arr['edit']['MessageError']);
            }
        } else {
            $this->form->getForm()->prepare();
            return ['form' => $this->form, 'id' => $this->id, 'add' => $this->crurl([$arr['name'], 'add'], $getUrls->get())];
        }
        $this->redir($arr['Redirect'], $getUrls->get());
        return null;
    }
    public function delete($arr)
    {
        $this->getFm($this->flashMessenger());
        $arr['getUrl']['name'] = $arr['table'];
        $this->setParam($arr['getUrl'],['count']);
        $getUrls = new MyURL($arr['getUrl']);

        try {
            $item = $this->getItem($arr);
            $this->entityManager->remove($item);
            $this->entityManager->flush();
            $this->fm->add($arr['delete']['MessageSuccess']);

        } catch (\Exception $ex){
            $this->fm->status = 'error';
            $this->fm->add($arr['delete']['MessageError'] . $ex->getMessage());
        }
        return $this->redir($arr['Redirect'], $getUrls->get());
    }
}

class BaseController extends BaseAdminController
{
    protected $auth;
    public function __construct($entityManager, AuthenticationService $authenticationService = null)
    {
        parent::__construct($entityManager);
        if (isset($authenticationService)){
            $this->auth = $authenticationService;
        }
    }
    public function onDispatch(MvcEvent $e)
    {
        if (isset($this->auth)){
            if (!$this->auth->hasIdentity()){
                return $this->redirect()->toRoute('auth-doctrine',['controller' => 'index', 'action' => 'login']);
            }
        }
        return parent::onDispatch($e); // TODO: Change the autogenerated stub
    }
}
