<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 29.11.2017
 * Time: 14:09
 */

namespace Custom;


use Zend\Form\Element;
use Zend\View\Helper\Partial;
use Zend\View\Helper\PartialLoop;
use Zend\View\Helper\Placeholder;
use Zend\View\Renderer\PhpRenderer;

class BaseView
{
    /**]
     * @var PhpRenderer $r
     */
    protected $r;
    /**
     * @var Placeholder $cont
     */
    protected $cont;
    /**
     * @var Partial $p
     */
    protected $p;
    /**
     * @var PartialLoop $pl
     */
    protected $pl;
    /**
     * @var array $col
     */
    protected $col;

    public function __construct(PhpRenderer $phpRenderer, $name, $class = null, $arr = null)
    {
        $this->r = $phpRenderer;
        if (isset($name)) $name = uniqid (rand(), true);
        $this->cont = $this->r->placeholder($name);
        if (isset($class)) $this->setClass($class);
        if (isset($arr)) {
            $this->addArr($arr);
        }
    }
    public function addTag($str)
    {
        return "<div>" . $str . "</div>";
    }
    public function style($vals)
    {
        $r = '';
        foreach ($vals as $k =>$val) {
            $r .= $k . ':' . $val . ';'; 
        }
        return $r;
    }
    public function compStyle(&$val)
    {
        if (array_key_exists('style', $val))
        {
            $val['style'] = $this->style($val['style']);
        }
    }
    public function addDiv($name,$vals)
    {
        foreach ($vals as $val) {
            $this->compStyle($val);
            if (is_array($val))
            $this->cont->append($this->r->partial($name, $val));
            else if (is_string($val))
                $this->cont->append($val);
        }
    }
    public function addArr($vals)
    {
        foreach ($vals as $k =>$val) {
            $this->addDiv($k, $val);
        }
    }
    public function add($val)
    {
        $this->cont->append($val);
    }
    public function addCol()
    {
        foreach ($this->col as $val) {
            $this->cont->append($val);
        }
    }
    public function addInCol($val)
    {
        $this->col[] = $val;
    }
    public function setClass($class)
    {
        $this->cont->setPrefix("<div class=\"{$class}\">");
        $this->cont->setPostfix("</div>");
    }
    public function addPrefixDiv($class)
    {
        $this->addPrefix("<div class=\"{$class}\">");
    }
    public function addPostfixDiv()
    {
        $this->addPostfix('</div>');
    }
    public function addPrefix($val)
    {
        $pr = $this->cont->getPrefix();
        $pr .= $val;
        $this->cont->setPrefix($pr);
    }
    public function addPostfix($val)
    {
        $pr = $this->cont->getPostfix();
        $pr .= $val;
        $this->cont->setPostfix($pr);
    }

    public function capStart()
    {
        $this->cont->captureStart();
    }

    public function capEnd()
    {
        $this->cont->captureEnd();
    }
    public function pr()
    {
        if (!empty($this->col)) $this->addCol();
        $p = $this->cont->toString();
        return $p;
    }
    public static function addNewDiv($r, $name, $class, $arr){
        $e = new BaseView($r, $name, $class, $arr);
        return $e->pr();
    }
    public static function createEl(PhpRenderer $r,Element $el, $arr)
    {
        $set = [];
        $attr = $el->getAttributes();
        if($el->getLabel()){
            $el->setLabelAttributes(['class', $arr['lclass']]);
            $label = $el->getLabel();
            if (isset($attr['required'])){
                $el->setLabel($el->getLabel().' *');
            }
            $set['lclass'] = $arr['lclass']; $set['label'] = $label;
        } else { $label = '';}
        $type = isset($attr['type']) ? $attr['type'] : '';
        $fe = '';
        switch ($type){
            case 'select':
                $fe = $r->formSelect($el);
                break;
            default:
                $fe = $r->formElement($el);
                break;
        }
        $set['el'] = $fe; $set['eclass'] = $arr['eclass'];
        $set = ['fe.phtml' => [$set]]; $name = $attr['name'];
        return self::addNewDiv($r, $name, $arr['class'], $set);
    }
    public static function createSub($r,$style,$style1,$par,$title)
    {
        $name = uniqid (rand(), true);
        $arr0 = ['div.phtml' => [
            ['class' => ' bb-a tac fs', 'style' => $style1, 'cont' => $par],
            ['class' => ' tac fs', 'style' => $style1, 'cont' => '&nbsp'],
        ]];
        $i6 = self::addNewDiv($r, $name, 'dib w-153 fs-0', $arr0);

        return [
            ['class' => 'fl br-a tac w-32 fs', 'style' => $style, 'cont' => $title],
            $i6,
        ];
    }
    public static function createLine($r,$style,$par,$title)
    {
        $name = uniqid (rand(), true);
        $arr = ['div.phtml' =>[
            ['class' => 'fl br-a tac w-32 fs', 'style' => $style, 'cont' => $title],
            ['class' => 'fl tac w-153 fs','style' => $style, 'cont' => $par],
        ]];
        return self::addNewDiv($r, $name, 'w100 bt-a dib', $arr);
    }
    public static function createStack($r, $class ,$vals)
    {
        $name = uniqid (rand(), true);
        $arr = ['div.phtml' => $vals];
        return self::addNewDiv($r, $name, $class, $arr);
    }
    public function createStyle($lh = null, $h = null, $w = null, $units = 'mm')
    {
        $st = [];
        if (isset($lh)) $st['line-height'] = $lh . $units;
        if (isset($h)) $st['height'] = $h . $units;
        if (isset($w)) $st['width'] = $w . $units;
        return $st;
    }
    public function w($val)
    {
        return 'w-'.$val;
    }
    public function head($par)
    {
        $style0 = $this->createStyle(10,10.6,null);
        $style3 = $this->createStyle(10,10.3,null);
        $style1 = $this->createStyle(10,20.6,null);
        $style4 = $this->createStyle(5,5.3,null);
        $style2 = $this->createStyle(5,10.3,null);
        $style5 = $this->createStyle(15,15.6,null);

        $rclass= ' fs-0';
        $bclass = ' fl br-a' . $rclass;
        $tclass = 'bb-a w100 oh';

        $arr = ['div.phtml' => [
            ['class' => 'fl br-a tac w-23 fs', 'style' => $style3, 'cont' => "ТЕПЛОМАШ"],
            ['class' => 'fl br-a tac w-23 fs', 'style' => $style3, 'cont' => $par['dep']],
            ['class' => 'fl br-a tac w-65 fs', 'style' => $style2, 'cont' => "ИЗВЕЩЕНИЕ<br>".$par['name']],
            ['class' => 'fl tac w-74 fs', 'style' => $style2, 'cont' => "ОБОЗНАЧЕНИЕ<br>".$par['desc']],
        ]];
        $this->addInCol(self::addNewDiv($this->r,'name1',$tclass, $arr));

        $arr = [['class' => ' bb-a tac fs', 'style' => $style4, 'cont' => "Дата выпуска"],
            ['class' => ' tac fs', 'style' => $style3, 'cont' => $par['date']]];
        $i1 = self::createStack($this->r,$this->w(46).$bclass,$arr);
        $arr = [['class' => ' bb-a tac fs', 'style' => $style4, 'cont' => "Срок изм."],
            ['class' => ' tac fs', 'style' => $style3, 'cont' => "&nbsp"]];
        $i2 = self::createStack($this->r,$this->w(23).$bclass,$arr);
        $arr = [['class' => ' bb-a tac fs', 'style' => $style4, 'cont' => "Срок дейсвия ПИ"],
            ['class' => ' tac fs', 'style' => $style3, 'cont' => "&nbsp"]];
        $i3 = self::createStack($this->r,$this->w(32).$bclass,$arr);
        $arr = [['class' => ' bb-a tac fs', 'style' => $style4, 'cont' => "Лист"],
            ['class' => ' tac fs', 'style' => $style3, 'cont' => $par['list']]];
        $i4 = self::createStack($this->r,$this->w('list').$bclass,$arr);
        $arr = [['class' => ' bb-a tac fs', 'style' => $style4, 'cont' => "Листов"],
            ['class' => ' tac fs', 'style' => $style3, 'cont' => $par['listov']]];
        $i5 = self::createStack($this->r,'dg'.$rclass,$arr);
        $arr =
            [$i1,$i2,
            ['class' => 'fl br-a tac w-42 fs', 'style' => $style5, 'cont' => "&nbsp"],
             $i3, $i4, $i5];
        $this->addInCol(self::createStack($this->r,$tclass,$arr));

        $arr = [['class' => ' bb-a tac w-40 fs', 'style' => $style4, 'cont' => "Код"],
            ['class' => ' tac fs', 'style' => $style4, 'cont' => $par['code']]];
        $i1 = self::createStack($this->r,'fl '.$this->w(40).$rclass,$arr);
        $arr = [['class' => 'fl br-a tac w-32 fs', 'style' => $style0, 'cont' => 'Причина'],
            ['class' => 'fl br-a tac w-32 fs', 'style' => $this->createStyle(10,10.6,111), 'cont' => $par['cause']],
            $i1];
        $this->addInCol(self::createStack($this->r,$tclass,$arr));

        $arr = self::createSub($this->r,$style1,$style3, $par['zadel'],'Указание о<br> заделе');
        $this->addInCol(self::createStack($this->r,'w100 oh',$arr));

        $arr = self::createSub($this->r,$style1,$style3, $par['vn'], 'Указание о<br> внедрении');
        $this->addInCol(self::createStack($this->r,'w100 oh bt-a mt-03',$arr));

        $this->addInCol(self::createLine($this->r,$style3,$par['inherit'],'Применяемость'));
        $this->addInCol(self::createLine($this->r,$style3,$par['mailto'],'Разослать'));
        $this->addInCol(self::createLine($this->r,$style3,$par['attach'],'Приложение'));
    }
    public function headIzm()
    {
        $tclass = 'bt-a w100 oh';
        $style3 = $this->createStyle(10,10.3,null);
        $arr = [
            ['class' => 'fl br-a tac w-13 fs', 'style' => $style3, 'cont' => "Изм."],
            ['class' => 'fl tac w-172 fs', 'style' => $style3, 'cont' => "Содержание изменения"],
        ];
        $this->addInCol(self::createStack($this->r,$tclass,$arr));
    }
    public function head1($par)
    {
        $tclass = 'w100 oh';
        $style3 = $this->createStyle(10,10.3,null);
        $arr = [
            ['class' => 'fl br-a tac w-69 fs', 'style' => $style3, 'cont' => $par['name']],
            ['class' => 'fl br-a tac w-74 fs', 'style' => $style3, 'cont' => '&nbsp'],
            ['class' => 'fl tac w-list fs', 'style' => $style3, 'cont' => 'Лист'],
            ['class' => 'fl tac w-list fs', 'style' => $style3, 'cont' => $par['list']],
        ];
        $this->addInCol(self::createStack($this->r,$tclass,$arr));
    }
}