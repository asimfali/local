<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 10.11.2017
 * Time: 13:30
 */

namespace Custom;


use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManager;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
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
     */
    public function __construct(EntityManager $entityManager, $arr = null)
    {
        $this->em = $entityManager;
        if ($arr == null) return;
        $this->query = $this->em->createQueryBuilder();
        $this->query
            ->select($arr['select'])
            ->from('Entity\\'. $arr['from'], $arr['select'])
            ->orderBy($arr['order'],$arr['desc']);
        if (isset($arr['where']))
        $this->query->where($arr['where']);
        $a = new DoctrinePaginator(new ORMPaginator($this->query,false));
        $this->p = new Paginator($a);
//        $p->setCurrentPageNumber((int) $this->params()->fromQuery('page',1));
    }
    public function setPaginator($count, $page = 1)
    {
        $this->p->setDefaultItemCountPerPage($count);
        $this->p->setCurrentPageNumber($page);
    }
    public function resetPaginator($el){
        $this->p = $el;
    }
    public function ret($name = null)
    {
        return $this->p;
    }
    public function addAction($arr, $confirm)
    {
        $url = '';
        if (isset($arr['id']))
            $url = '&' . 'id=' . $arr['id'];
        $this->actions[] = "<a href=\"{$arr['url']}?name={$arr['name']}{$url}\" $confirm>{$arr['txt']}</a>";
    }
    public function camelCase($str)
    {
        $str = ucwords($str, '_');
        $str = ucfirst($str);
        $str = str_replace('_', '', $str);
        return $str;
    }
    public function tags($str, $t = 'td'){
        return '<'.$t.'>'.$str.'</'.$t.'>';
    }
    public function getContent($item,$name)
    {
        if (is_array($name)){
            $par = call_user_func([$item, 'get'. $this->camelCase($name[0])]);
            $this->contents[] = $this->tags(call_user_func([$par, 'get'. $this->camelCase($name[1])]));
        } else {
            $this->contents[] = $this->tags(call_user_func([$item, 'get'.$this->camelCase($name)]));
        }
    }
    public function set($arr)
    {
        $this->css = $arr['class'];
        foreach ($arr['ths'] as $th=>$item) {
            $this->headers[] = $this->tags($th, 'th');
        }
        foreach ($this->p as $item) {
            $this->contents[] = '<tr>';
            foreach ($arr['ths'] as $k => $n) {
                if ($k == 'Действие'){
                    foreach ($n as $key => $d) {
                        $url = '/' . $arr['name'] . $arr['admin'] . '/' . $key . '/' . call_user_func([$item, 'getId']) . '/';
                        $onClick = '';
                        if ($key == 'delete')
                            $onClick = "onclick=\"if (confirm('Удалить запись?')){ document.location = this.href; } return false;\"";
                        $this->addAction(['url' => $url, 'txt' => $d, 'name' => $arr['table'], 'id' => $arr['id']],$onClick);
                    }
                    $tmp = '';
                    foreach ($this->actions as $action) {
                        $tmp .= $action . '  ';
                    }
                    $this->contents[] = $this->tags($tmp);
                    unset($this->actions);
                } else {
                    $this->getContent($item, $n);
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