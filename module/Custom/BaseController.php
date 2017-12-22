<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 20.10.2017
 * Time: 11:13
 */

namespace Custom;


use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\View\Model\ViewModel;

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
    /**
     * @var MyURL $getUrls
     */
    protected $getUrls;
    /**
     * @var $fields
     */
    protected $fields;
    /**
     * @var ViewModel $view
     */
    protected $view;
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
    public function redir($path, $get = null)
    {
        if (empty($get))
            return $this->redirect()->toRoute($path);
        else {
            $url = $this->crurl($path, $get);
            return $this->redirect()->toUrl($url);
        }

    }
    public function toArray($ent, $act)
    {
        $arr = null;
        /**
         * @var Collection $tmp
         */
        $tmp = call_user_func([$ent, $act[0]]);
        $i = 1;
        foreach ($tmp as $item) {
            $arr['Key'.$i] = $this->getValue($item, $act[1]);
            $arr['Val'.$i] = $this->getValue($item, $act[2]);
            $i++;
        }
        return $arr;
    }
    public function getValue($ob,$name)
    {
        if (is_array($name)){
            $ob = call_user_func([$ob,$name[0]]);
            $ob = call_user_func([$ob,$name[1]]);
        } else {
            $ob = call_user_func([$ob,$name]);
        }
        return $ob;
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
    public function crurl($arr, $name = null){
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
    public function upload($path, $name = null, $pdf = false)
    {
        $this->getReq(); $f = null;
        if ($this->req->isPost()){
            $f = new Files($_FILES['upload']);
            $f->copy($path);
        } else if ($pdf){
            $f = new Files(null);
            $f->pdf($path);
        }
        return ['names' => $f, 'path' => $name];
    }
    public function index($arr, $count = 10, $get = null)
    {
        if (empty($arr)) return ['default' => $this->names, 'base' => '', 'show' => $this->crurl(['izv', 'admin' ,'show'])];
        $this->getReq();
        $q = new DQuery($this->entityManager, $arr['query']);
        $arr['getUrl']['name'] = $arr['table'];
        $arr['getUrl']['count'] = $count;
        $getUrls = new MyURL($arr['getUrl']);
        $page = $_GET['page'];
        if (empty($page)) $page = 1;
        $q->setPaginator($count, $page);
        $this->getFm($this->plugin('flashMessenger'));
        $q->set(['name' => $arr['name'], 'class' => $arr['css'], 'ths' => $arr['ths'], 'get' => $get,
            'table' => $arr['table'], 'thClasses' => $arr['thClasses'], 'tdClasses' => $arr['tdClasses']]);
        return ['fm' => $this->fm, 'route' => $arr['name'], 'q' => $q, 'name' => $q->ret(), 'table' => $getUrls->get(), 'desc' => $arr['desc'],
            'add' => $this->crurl([$arr['name'], 'add'], $getUrls->get())];
    }
    public function baseView($b,$n,$vals)
    {
        $this->view = $this->setTempl($b,null);
        $i = 0;
        foreach ($vals as $k => $val) {
            $e = $this->setTempl($n[$i], $val);
            $this->view->addChild($e, $k);
            $i++;
        }
        return $this->view;
    }
    public function setTempl($n,$cont)
    {
        $v = new ViewModel($cont);
        $v->setTemplate($n);
        return $v;
    }
    public function indexPDO($arr, $count = 10, $get = null)
    {
        if (empty($arr)) return ['default' => $this->names, 'base' => '', 'show' => $this->crurl(['izv', 'admin' ,'show'])];
        $this->getReq();
        $q = new PDOQuery(['t' => $arr['table'],'j' => $arr['joins'], 'out' => $arr['ths'],'w' => $arr['where'][0],'c' => $arr['columns'] , 's' => $arr['sort']]);
        $q->build();
        $q->run($arr['where'][1],true);
        $arr['getUrl']['name'] = $arr['table'];
        $arr['getUrl']['count'] = $count;
        $getUrls = new MyURL($arr['getUrl']);
        $page = $_GET['page'];
        if (empty($page)) $page = 1;
        $q->setPaginator($count, $page);
        $this->getFm($this->plugin('flashMessenger'));
        $q->set(['name' => $arr['name'], 'class' => $arr['css'], 'ths' => $arr['ths'], 'get' => $get,
            'table' => $arr['table'], 'thClasses' => $arr['thClasses'], 'tdClasses' => $arr['tdClasses']]);
        return ['fm' => $this->fm, 'route' => $arr['name'], 'q' => $q, 'name' => $q->p, 'table' => $getUrls->get(), 'desc' => $arr['desc'],
            'add' => $this->crurl([$arr['name'], 'add'], $getUrls->get())];
    }
    public function set($arr, $is_form = false, $is_req = false, $sets = null, $ent= null)
    {
        $this->getFm($this->plugin('flashMessenger'));
        $arr['getUrl']['name'] = $arr['table'];
        $this->setParam($arr['getUrl'],['count']);
        $this->getUrls = new MyURL($arr['getUrl']);
        if ($is_form){
            if (empty($ent))
            $this->fields = new $arr['entity']();
            else $this->fields = $ent;
            if (isset($sets)){
                foreach ($sets as $k => $set) {
                    call_user_func_array([$this->fields,'set'.$k],[$set]);
                }
            }
            $this->form = new MyForm($this->fields, $this->entityManager);
            $this->form->setValue('hidden', 'value', $arr['table']);
        }
        if ($is_req){
            $this->getReq();
        }
    }
    public function flush($arr, $item)
    {
        $this->form->setData($this->req->getPost());
        if ($this->form->getForm()->isValid()){
            $this->entityManager->persist($item);
            $this->entityManager->flush();
            $this->fm->add($arr['add']['MessageSuccess']);
        } else {
            $this->error($arr['add']['MessageError']);
        }
    }
    public function showForm($arr, $v = null)
    {
        $this->form->getForm()->prepare();
        $r = ['form' => $this->form, 'id' => $this->id, 'add' => $this->crurl([$arr['name'], 'add'], $this->getUrls->get())];
        if (isset($v)) $r = $r + $v;
        return $r;
    }

    public function numberIzv($p, &$dir, $inc = true)
    {
        if ($inc) $p++;
        $dir = $p;
        $m = null;
        preg_match('/(\d{2})(\d{3})/is', $p, $m);
        return 'ТПМШ.' . $m[2] . '-' . $m[1];
    }

    public function izvNumber($p)
    {
        $m = null;
        preg_match('/(\d{3})-(\d{2})/is', $p, $m);
        return $m[2] . $m[1];
    }

    public function lastNumber($table,$sort)
    {
        $query = $this->entityManager->createQueryBuilder();
        $query
            ->select('a')
            ->from('Entity\\'. $table, 'a')
            ->orderBy('a.'.$sort,'DESC')
            ->setMaxResults(1);
        $p = $query->getQuery()->getResult();
        return $p[0];
    }
    public function check($name, $val)
    {
        $rep = $this->entityManager->getRepository('Entity\\'.$name);
        $el = $rep->findBy($val);
        return (empty($el)) ? false : true;
    }
    public function add($arr)
    {
        $this->set($arr, true, true);
        $this->form->setAction($arr['add']['Action']);
        if ($this->req->isPost()){
            $this->flush($arr, $this->fields);
        } else {
            return $this->showForm($arr);
        }
        $this->redir([$arr['Redirect']],  $this->getUrls->get());
        return null;
    }
    public function edit($arr)
    {
        $this->set($arr, true, true);
        $item = $this->getItem($arr);
        $this->form->getForm()->bind($item);
        if($this->req->isPost()){
            $this->flush($arr, $item);
        } else {
            return $this->showForm($arr);
        }
        $this->redir([$arr['Redirect']], $this->getUrls->get());
        return null;
    }
    public function delete($arr)
    {
        $this->set($arr);

        try {
            $item = $this->getItem($arr);
            $this->entityManager->remove($item);
            $this->entityManager->flush();
            $this->fm->add($arr['delete']['MessageSuccess']);

        } catch (\Exception $ex){
            $this->fm->status = 'error';
            $this->fm->add($arr['delete']['MessageError'] . $ex->getMessage());
        }
        return $this->redir([$arr['Redirect']], $this->getUrls->get());
    }
    public function itemAdd($arr, $table = null)
    {
        $this->getFm($this->plugin('flashMessenger'));
//        $cl = new $arr['entity']();
        $this->form = new MyForm(null, $this->entityManager);
        $this->form->addDoctrine(['prop' => $arr['prop'], 'label' => $arr['label'], 'criteria' => $arr['where'],
            'class' => 'form-control', 'type' => 'ObjectSelect', 'name' => 'ua', 'target' => $arr['refEntity']]);
        $this->form->add('submit', ['attr' => ['value' => 'Добавить', 'id' => 'btn_submit', 'class' => 'btn btn-primary']]);
        $this->getReq();
        $data = $this->req->getPost();
        $pid = $this->params()->fromRoute();
        $p = $this->entityManager->getRepository($arr['entity']);
        $elem = $p->find($pid['id']);
        if ($this->req->isPost()) {
            $id = $data->get('ua', null);
            $r = $this->entityManager->getRepository($arr['refEntity']);
            $item = $r->find($id);
            $cat = call_user_func([$item, 'get' . $arr['itemName']]);
            if (isset($arr['noCopy'])){
                call_user_func_array([$item, 'set' . $arr['itemName']], [$elem]);
                $this->entityManager->persist($item);
                $this->entityManager->flush();
            }
            else if ($cat == null || $cat != $elem) {
                $item = clone $item;
                $this->entityManager->detach($item);
                call_user_func_array([$item, 'set' . $arr['itemName']], [$elem]);
                $this->entityManager->persist($item);
                $this->entityManager->flush();
            }
        }
//            $this->redir($_SERVER['SCRIPT_NAME'],);
//            header('Location: ' . $_SERVER["HTTP_REFERER"]);
        $cols = call_user_func([$elem, 'get' . $arr['collection']]);
        $q = new DQuery($this->entityManager);
        $q->resetPaginator($cols);
        unset($table['ths']['Действие']);
        $table['ths']['Действие'] = ['delete-item' => 'Удалить элемент'];
        $q->set(['name' => $table['name'], 'class' => $table['css'], 'ths' => $table['ths'],
            'table' => $table['table'], 'pname' => $arr['table'] , 'id' => $pid['id']]);
        $this->form->getForm()->prepare();
        return ['form' => $this->form, 'id' => $this->id, 'q' => $q];
    }
    public function deleteItem($arr, $id)
    {
        $r = $this->entityManager->getRepository($arr['refEntity']);
        $p = $this->entityManager->getRepository($arr['entity']);
        $pid = $this->params()->fromRoute();
        $item = $r->find($pid['id']);
        if (isset($arr['noCopy'])){
            $elem = $p->find($id);
            call_user_func_array([$elem, 'remove' . $arr['collections']], [$item]);
            call_user_func_array([$item, 'set' . $arr['itemName']], [null]);
            $this->entityManager->persist($item);
            $this->entityManager->flush();
        } else {
            $elem = $p->find($id);
            call_user_func_array([$elem, 'remove' . $arr['collections']], [$item]);
            $this->entityManager->remove($item);
            $this->entityManager->flush();
        }
        $this->redir([$arr['name'],'add-item',$id],"?name=" . $arr['table']);
    }
}

class BaseAddController extends BaseAdminController
{
    protected $auth;
    public function __construct($entityManager, AuthenticationService $authenticationService = null)
    {
        parent::__construct($entityManager);
        if (isset($authenticationService)){
            $this->auth = $authenticationService;
        }
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
        return parent::onDispatch($e);
    }
}
