<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 19.12.2017
 * Time: 8:48
 */

namespace Custom;

use \PDO as PDO;
use Zend\Paginator\Paginator;

class PDOQuery
{
    private $host = '127.0.0.1';
    private $db = 'teplomash';
    private $user = 'root';
    private $pass = '123';
    private $charset = 'utf8';
    private $table = '';
    private $out = '';
    private $where = '';
    private $join = '';
    private $sort = '';
    private $qSelect = 'SELECT ';
    /**
     * @var \PDO $pdo
     */
    private $pdo;
    private $prep;
    /**
     * @var Paginator $p
     */
    public $p;
    public $vals = null;
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
    protected $aliases = [];

    public function __construct($arr , $usr = null, $pass = null)
    {
        if (isset($this->pdo)){
            $this->init($arr);
            return;
        }
        if (isset($usr)) $this->user = $usr;
        if (isset($pass)) $this->pass = $pass;
        $dns = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
        $opt = [
            PDO::ATTR_ERRMODE                => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE     => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES       => false,
        ];
        $this->pdo = new PDO($dns, $this->user, $this->pass, $opt);
        $this->init($arr);
    }

    public function unionArr($arr)
    {
        $r = '';
        foreach ($arr as $k => $item) {
            if ($k === 'Действие') continue;
            $r .=  $this->getVal($item) . ', ';
        }
        $r = trim($r, ', ');
        return $r;
    }

    public function setWhere($arr)
    {
        $l = ''; $r = '';
        if (is_string($arr['act'])) {
            if (is_array($arr['name'])){
                $l = '('; $r = '(';
                foreach ($arr['name'] as $item) {
                    $l .=  'p.' . $item . ', ';
                }
                if (is_array($arr['val'])){
                    foreach ($arr['val'] as $item){
                        $r .= $item;
                    }
                } else {
                    foreach ($arr['name'] as $item) {
                        $r .= '?, ';
                    }
                }

                $l = trim($l,', '); $r = trim($r,', ');
                $l .= ')'; $r .= ')';
            } else {
                $tmp = $this->createWhere($arr['table'], $arr['name'], $arr['act'], $arr['count']);
                $l = $tmp[0]; $r = $tmp[1];
            }
            $this->where .= $l . $arr['act'] . $r;
            if (isset($arr['type'])){
                $this->where .= ' ' . $arr['type'] . ' ';
            }
        } else if(is_array($arr)) {
            foreach ($arr as $item) {
                $this->setWhere($item);
            }
        }
    }

    public function createWhere($table, $p, $acts, $count = null)
    {
        $l = $table . '.'; $r = '?';
        if (empty($table)) $l = '';
        $acts = trim($acts);
        switch ($acts)
        {
            case 'BETWEEN':
                $l .= $p . ' ';
                $r = " ? AND ? ";
                break;
            case 'IN':
                $l .= $p . ' ';
                if (isset($count))
                    $r = ' (' . str_repeat('?, ', $count-1) . '?) ';
                else $r = ' ? ';
                break;
            default:
                $l .= $p;
                break;
        }
        return [$l, $r];
    }

    public function init($arr)
    {
        if (isset($arr['t'])) $this->setTable($arr['t']);
        if (isset($arr['out'])){
            if (isset($arr['c'])) $arr['out'] = $arr['out'] + $arr['c'];
            if (is_array($arr['out']))
                $this->out = $this->unionArr($arr['out']);
            else
                $this->out = $arr['out'];
        }
        if (isset($arr['w'])) $this->setWhere($arr['w']);
        if (isset($arr['j'])) $this->setJoin($arr['j']);
        if (isset($arr['s'])) $this->setSort($arr['s']);
    }

    public function build()
    {
        if (empty($this->out)) $this->qSelect .= '* ';
        else $this->qSelect .= $this->out;
        $this->qSelect .= ' FROM `' . $this->table . '` p ';
        $this->qSelect .= $this->join;
        if (!empty($this->where)){
            $this->qSelect .= "WHERE " . $this->where;
        }
        $this->qSelect .= $this->sort;
    }
    public function run($vals, $fetchAll = false)
    {
        $this->prep = $this->pdo->prepare($this->qSelect);
        $this->prep->execute($vals);
        if ($fetchAll){
            $this->vals = $this->prep->fetchAll(PDO::FETCH_NAMED);
        } else {
            while ($r = $this->prep->fetch()) {
                $this->vals[] = $r;
            }
        }
        $this->p = new Paginator(new Dates($this->vals));
    }

    public function setPaginator($count, $page)
    {
        $this->p->setDefaultItemCountPerPage($count);
        $this->p->setCurrentPageNumber($page);
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param string $table
     */
    public function setTable($table)
    {
        if (is_array($table)){
            foreach ($table as $item) {
                $this->table .= $item . ',';
            }
            $this->table = trim($this->table,',');
        } else {
            $this->table = $table;
        }
    }

    /**
     * @return string
     */
    public function getWhere()
    {
        return $this->where;
    }

    /**
     * @return string
     */
    public function getQSelect()
    {
        return $this->qSelect;
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
        return '';
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
    public function expl($vals,$delimiter)
    {
        $tmp = [];
        if (stripos($vals, $delimiter) > 0){
            $tmp = explode($delimiter,$vals);
        }
        foreach ($tmp as &$v){
            $v = trim($v);
        }
        return $tmp;
    }
    public function getInArr($v, $f, $num = 1)
    {
        $v = $v[$f];
        if(is_array($v)) return $v[$num];
        else return $v;
    }
    public function getContent($item,$name)
    {
        $c = $this->getVal($name);
        if (array_key_exists($c,$item))
        {
            return $item[$c];
        }
        if (stripos($c,'.') > 0){
            $c = preg_replace('/(\w*\.)/is','',$c);
        }
        if (stripos($c,',') > 0){
            $v = $this->expl($c,','); $tmp = '';
            foreach ($v as $g){
                $tmp .= $this->getInArr($item,$g,0) . ' ';
            }
            return $tmp;
        }
        $c = $this->getInArr($item,$c,$name[1]);
        return $c;
    }
    public function getVal($name)
    {
        $val = '';
        if (is_array($name)){
            $val = reset($name);
        } else
            $val = $name;
        if (is_array($val)){
            $val = $this->unionArr($val);
        }
        return $val;
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
                        $url = '/' . $arr['name'] .'/' . $key . '/' . $item['id'] . '/';
                        $url = rtrim($url,'/');
                        $url .= '/';
                        $onClick = '';
                        if (isset($arr['get'])){
                            $val = $this->getContent($item,$arr['get']);
                            $get = [$namePDF => $val . '.pdf'];
                        }
                        if ($key == 'delete')
                            $onClick = "onclick=\"if (confirm('Удалить запись?')){ document.location = this.href; } return false;\"";
                        else if($d == 'PDF')
                            $onClick = "target=\"_blank\"";
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

    /**
     * @return string
     */
    public function getJoin()
    {
        return $this->join;
    }

    public function quot($val)
    {
        return '`' . $val . '`';
    }

    /**
     * @param array $join
     */
    public function setJoin($join)
    {
        foreach ($join as $item) {
            $this->join .= ' JOIN ';
            $this->join .= $this->quot($item['table']) . ' ' . $item['alias'] . ' ON ' .
                $item['alias'] . '.' . $item['id'] . ' ' . $item['act'] . ' p.' . $item['pid'] . ' ';
        }
    }

    /**
     * @return string
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param array $sort
     */
    public function setSort($sort)
    {
        $this->sort .= ' ORDER BY ';
        foreach ($sort as $item) {
            if (is_array($item)){
                $this->sort .= $item[0] . ' ' . $item[1] . ',';
            } else $this->sort .= $item . ',';
        }
        $this->sort = trim($this->sort,',');
    }
}

