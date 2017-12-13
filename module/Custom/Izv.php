<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 07.12.2017
 * Time: 8:50
 */

namespace Custom;


use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\Collection;
use Entity\Department;
use Entity\IzvContent;
use Entity\Templates;
use Entity\User;
use Zend\Http\Request;
use Zend\View\Renderer\PhpRenderer;

class Izv
{
    /**
     * @var \Entity\Izv $ent
     */
    protected $ent;
    /**
     * @var IzvContent $cont
     */
    protected $cont;
    protected $entityManager;
    /**
     * @var Department $dep
     */
    public $dep;
    /**
     * @var User $user
     */
    public $user;
    protected $alias;
    /**
     * @var Templates $tmpl
     */
    protected $tmpl;
    /**
     * @var Files $f
     */
    public $f;
    public $dir;
    public $path;
    public $pdf;
    public $dxf;
    public function __construct(\Entity\Izv $izv, User $user,EntityManager $entityManager)
    {
        $this->ent = $izv;
        $this->entityManager = $entityManager;
        $this->user = $user;
        $this->dep();
    }
    public function set($vals)
    {
        foreach ($vals as $k=>$val) {
            call_user_func_array([$this->ent,'set'.lcfirst($k)],[$val]);
        }
    }
    public function setIzv($val)
    {
        $this->ent = $val;
    }
    public function dep()
    {
        $this->dep = $this->user->getUsrDepartment();
        $this->alias = $this->dep->getAlias();
    }
    public function init($p = null)
    {
        $this->tmpl = $this->findValue('Templates',['name' => $this->alias]);
        if(isset($p)) {
            $this->path = $p . '/izv/' . $this->dir;
            $this->f = new Files($this->path);
        }
    }
    public function upload(Request $req, $name = null, $pdf = false)
    {
        $f = null;
        if ($req->isPost()){
            $f = new Files($_FILES['upload']);
            $f->copy($this->path);
        } else if ($pdf){
            $f = new Files(null);
            $f->pdf($this->path);
        }
        return ['names' => $f, 'path' => $name];
    }

    public function count()
    {
        $this->pdf = $this->f->countF('*.pdf');
        $this->dxf = $this->f->countF('*.dxf');
        $r = '';
        if (!empty($this->pdf)) $r .= $this->pdf . ' эл. черт в PDF; ';
        if (!empty($this->pdf)) $r .= $this->dxf . ' разв. детали в DXF; ';
        return $r;
    }
    public function toArray($ent, $act, &$arr)
    {
        /**
         * @var Collection $tmp
         */
        $tmp = call_user_func([$ent, $act[0]]);
        $i = 1;
        foreach ($tmp as $item) {
            $name = $i;
            $arr[$name]['act'] = $this->getValue($item, $act[1]);
            $arr[$name]['user'] = $this->getValue($item, $act[2]);
            $arr[$name]['sign'] = '&nbsp';
            $arr[$name]['date'] = '&nbsp';
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
    public function findValue($name, $criteria)
    {
        $ent = $this->entityManager->getRepository('Entity\\'.$name);
        return $ent->findOneBy($criteria);
    }
    public function setValue($ob, $name)
    {
        if (isset($ob))
            return call_user_func([$ob, 'get'.lcfirst($name)]);
        return '';
    }

    public function addRec($vals)
    {
        /**
         * @var IzvContent $cont
         */
        $cont = new IzvContent();
        $cont->setLink($vals['link']);
        $cont->setName($vals['numberIzv']);
        $cont->setContent($vals['cont']);
        $cont->setAct($vals['act']);
        $cont->setIzv($this->ent);
//        $this->entityManager->persist($cont);
        $this->ent->addIzvContent($cont);
    }
    public function replNumber($val)
    {
        if (is_int($val)) return $val;
        $m = null;
        preg_match('/(\d+)-(\d+)/is',$val,$m);
        return $m[2] . $m[1];
    }

    public function save($post)
    {
        $vals = $post['vals'];
        $recs = $post['recs'];
        foreach ($recs as $rec) {
            $this->addRec($rec);
        }
        $vals['numberIzv'] = $this->replNumber($vals['numberIzv']);
        $vals['department'] = $this->dep;
        $vals['date'] = \DateTime::createFromFormat('d.m.y', $vals['date']);
        $vals['reason'] = $this->findValue('Fields',['id' => $vals['reason']]);
        $vals['zadel'] = $this->findValue('Fields',['id' => $vals['reason']]);
        $vals['impl'] = $this->findValue('Fields',['id' => $vals['reason']]);
        $vals['usrFirstName'] = $this->user;
        $this->set($vals);
        $this->entityManager->persist($this->ent);
        $this->entityManager->flush();
        return true;
    }
    public function numberIzv($p, $inc = true)
    {
        if ($inc) $p++;
        $this->dir = $p;
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
    public function pr($post)
    {
        $vals = $post['vals'];
        $cont = null;
        foreach ($post['recs'] as $rec) {
            $cont[$rec['p']][] = $rec;
        }
        $name = $this->user->getUsrFirstName();
        $code = null;
        if (!empty($vals['reason']))
        $code = $this->findValue('Fields',['id' => $vals['reason']]);
        $zadel = $this->findValue('Fields',['id' => $vals['zadel']]);
        $impl = $this->findValue('Fields',['id' => $vals['impl']]);
        if ($vals['department'] != $this->dep->getId())
        {
            $this->dep = $this->findValue('Department',['id' => $vals['department']]);
            $this->alias = $this->dep->getAlias();
        }
        $this->init();
        $foot['0'] = ['act' => 'Составил', 'user' => $name, 'sign' => '&nbsp', 'date' => $vals['date']];
        $foot = $this->toArray($this->tmpl, ['getActions','getUsrAction',['getUser', 'getUsrFirstName']], $foot);

        $foot['CreateDate'] = $vals['date'];
        $p = $_SERVER['DOCUMENT_ROOT'] . '/img/test.png';
        $foot['CreateSign'] = $p;
//        $sign = file_get_contents($p);
        $par = ['list' => count($cont) > 1 ? "1" :'&nbsp', 'listov' => count($cont), 'date' => '30.11.17', 'desc' => 'см. ниже', 'code' => '1'];
        $par = $vals + $par;
        $par['department'] = $this->alias;
        $par['reason'] = $this->setValue($code,'txt');
        $par['code'] = $this->setValue($code,'code');
        $par['zadel'] = $this->setValue($zadel,'txt');
        $par['impl'] = $this->setValue($impl,'txt');
        $par['mailto'] = $vals['mailto'];
        return ['par' => $par, 'foot' => $foot, 'cont' => $cont];
    }
}