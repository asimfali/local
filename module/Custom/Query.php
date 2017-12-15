<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 10.11.2017
 * Time: 13:30
 */

namespace Custom;


use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManager;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\I18n\Validator\DateTime;
use Zend\Paginator\Paginator;
use Zend\View\Renderer\PhpRenderer;

class Query
{
    /**
     * @var EntityManager
     */
    protected $em;
    /**
     * @var QueryBuilder
     */
    protected $query;
    /**
     * @var Paginator
     */
    protected $p;
    /**
     * @var array $headers
     */
    protected $headers;
    /**
     * @var array $contents
     */
    protected $contents;
    /**
     * @var array $actions
     */
    protected $actions;
    /**
     * @var
     */
    protected $css;

    /**
     * Query constructor.
     * @param EntityManager $entityManager
     * @param $arr - array
     * @param string $filter
     * @internal param int $max
     */
    public function __construct(EntityManager $entityManager, $arr = null, $filter = null)
    {
        $this->em = $entityManager;
        if ($arr == null) return;
        $this->query = $this->em->createQueryBuilder();
        if (is_array($arr['order'])) {
            $order = array_shift($arr['order']);
            $desc = array_shift($arr['desc']);
        } else {
            $order = $arr['order'];
            $desc = $arr['desc'];
        }
        $this->query
            ->select($arr['select'])
            ->from('Entity\\'. $arr['from'], $arr['select'])
            ->orderBy($order,$desc);
        if (is_array($arr['order']) && !empty($arr['order'])){
            $i = 0;
            foreach ($arr['order'] as $item) {
                $this->query->addOrderBy($item,$arr['desc'][$i]);
                $i++;
            }
        }
        if (isset($arr['where']))
        $this->query->where($arr['where']);
//        if (isset($filter))
//        {
//            $tmp = $this->parse($filter, $this->query, $arr['select']);
////            $this->query->where($this->query->expr()->like('17001','a.number_izv'));
////            $this->query->where($tmp);
////            $this->query->add('where', new Comparison('typeCurtain','=',1));
//        }
        $a = new DoctrinePaginator(new ORMPaginator($this->query,false));
        $this->p = new Paginator($a);
//        $p->setCurrentPageNumber((int) $this->params()->fromQuery('page',1));
    }
    public function parse($val, QueryBuilder $qb, $base)
    {
        $base .= '.';
        $spls = explode(';',$val);
        foreach ($spls as $k=>$spl) {
            $tokens = explode(' ',$spl);
            if (count($tokens) != 3) {unset($spl[$k]); continue;}
            switch ($tokens[1])
            {
                case '<':
                    $spls[$k] = $qb->expr()->lt($base . $tokens[0],$tokens[2]);
                    break;
                case '>':
                    $spls[$k] = $qb->expr()->gt($base . $tokens[0],$tokens[2]);
                    break;
                case '=':
                    $spls[$k] = $qb->expr()->eq($base . $tokens[0],$tokens[2]);
                    break;
            }
        }
        if (count($spls) == 1) 
            return $spls[0];
        else {
            return $qb->expr()->andX(...$spls);
        }
    }
    public function setPaginator($count, $page = 1)
    {
        $this->p->setDefaultItemCountPerPage($count);
        $this->p->setCurrentPageNumber($page);
    }
    public function resetPaginator($el){
        $this->p = $el;
    }
    public function ret()
    {
        return $this->p;
    }
    public function addAction($arr, $confirm)
    {
        $url = '';
        if (isset($arr['id']))
            $url .= '&' . 'id=' . $arr['id'];
        if (isset($arr['pname']))
            $url .= '&' . 'pname=' . $arr['pname'];
        $get = '';
        if(isset($arr['get']))
        {
            foreach ($arr['get'] as $k=>$item) {
                $get .= '&' . $k . '=' . $item;
            }
        }
        $this->actions[] = "<a href=\"{$arr['url']}?name={$arr['name']}{$get}{$url}\" $confirm>{$arr['txt']}</a>";
    }
    public function camelCase($str)
    {
        $str = ucwords($str, '_');
        $str = ucfirst($str);
        $str = str_replace('_', '', $str);
        return $str;
    }

    public function replaceDate($str)
    {
        switch ($str)
        {
            case 'January':
                return 'Январь';
                break;
            case 'February':
                return 'Февраль';
                break;
            case 'March':
                return 'Март';
                break;
            case 'April':
                return 'Апрель';
                break;
            case 'May':
                return 'Май';
                break;
            case 'June':
                return 'Июнь';
                break;
            case 'July':
                return 'Июль';
                break;
            case 'August':
                return 'Август';
                break;
            case 'September':
                return 'Сентябрь';
                break;
            case 'October':
                return 'Октабрь';
                break;
            case 'November':
                return 'Ноябрь';
                break;
            case 'December':
                return 'Декабрь';
                break;
        }
    }
    public function tags($str, $t = 'td', $class = null){
        $r = '';
        if (isset($class)){
            $class = 'class="' . $class . "\"";
        } else $class = '';
        if (get_class($str) == 'DateTime')
        {
            $r .= $this->replaceDate($str->format('F'));
            $r .= ' ' . $str->format('Y');
        } else $r = $str;
        return '<'.$t.' '.$class. '>'.$r.'</'.$t.'>';
    }
    public function getContent($item,$name)
    {
        if (is_array($name) && !empty($name)){
            $first = array_shift($name);
            return $this->getContent(call_user_func([$item, 'get'. $this->camelCase($first)]),$name);
        } else if (empty($name)){
            return $item;
        } else
            return call_user_func([$item, 'get'.$this->camelCase($name)]);
    }
    public function set($arr)
    {
        $namePDF = ''; $get = null;
        if (isset($arr['get'])){
            $namePDF = array_shift($arr['get']);
        }
        $this->css = $arr['class'];
        $i = 0;
        foreach ($arr['ths'] as $th=>$item) {
            $this->headers[] = $this->tags($th, 'th', $arr['thClasses'][$i]);
            $i++;
        }
        foreach ($this->p as $item) {
            $tr = '<tr>';
            $this->contents[] = $tr;
            $i = 0;
            foreach ($arr['ths'] as $k => $n) {
                if ($k == 'Действие'){
                    foreach ($n as $key => $d) {
                        $url = '/' . $arr['name'] .'/' . $key . '/' . call_user_func([$item, 'getId']) . '/';
                        $onClick = '';
                        if (isset($arr['get'])){
                            $val = $this->getContent($item,$arr['get']);
                            $get = [$namePDF => $val . '.pdf'];
                        }
                        if ($key == 'delete')
                            $onClick = "onclick=\"if (confirm('Удалить запись?')){ document.location = this.href; } return false;\"";
                        $this->addAction(['url' => $url, 'txt' => $d, 'name' => $arr['table'], 'id' => $arr['id'], 'get' => $get, 'pname' => $arr['pname']],$onClick);
                    }
                    $tmp = '';
                    foreach ($this->actions as $action) {
                        $tmp .= $action . '  ';
                    }
                    $this->contents[] = $this->tags($tmp);
                    unset($this->actions);
                } else {
                    if (is_array($n) && array_key_exists('trim',$n))
                    {
                        $count = $n['trim'];
                        unset($n['trim']);
                    }
                    $cont = $this->getContent($item, $n);
                    if (isset($count)) {
                        if (strlen($cont) > $count)
                            $cont = substr($cont, 0, $count) . '...';
                        $count = null;
                    }
                    $this->contents[] = $this->tags($cont, 'td', $arr['tdClasses'][$i]);
                    $i++;
                }
            }
            $this->contents[] = '</tr>';
        }
    }
    public function prTable()
    {
        echo "<table class=\"{$this->css}\">";
        echo "<tbody><thead><tr>";
        foreach ($this->headers as $header) {
                echo $header;
            }
        echo "</tr></thead>";
        foreach ($this->contents as $content) {
            echo $content;
        }
        echo "</tbody></table>";
    }
}