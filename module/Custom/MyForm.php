<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 10.11.2017
 * Time: 11:33
 */

namespace Custom;


use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder;
use Zend\Form\Annotation\Attributes;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\View\Renderer\PhpRenderer;

class MyForm
{
    /**
     * @var EntityManager
     */
    protected $em;
    /**
     * @var AnnotationBuilder
     */
    protected $ab;
    /**
     * @var Form
     */
    protected $form;
    /**
     * @var
     */
    protected $elements;
    /**
     * @var Element
     */
    protected $el;
    /**
     * @var Attributes
     */
    protected $attr;
    /**
     * @var PhpRenderer
     */
    protected $r;
    /**
     * @var \Zend\Form\View\Helper\Form
     */
    protected $helper;
    /**
     * @var DoctrineObject $hydrator
     */
    public $hydrator;
    /**
     * MyForm constructor.
     * @param $class
     * @param EntityManager $entityManager
     */
    public function __construct($class ,EntityManager $entityManager)
    {
        $this->em = $entityManager;
        if ($class == null){
            $this->form = new Form();
            return;
        }
        $this->ab = new AnnotationBuilder($this->em);
        $name = get_class($class);
        $this->form = $this->ab->createForm(new $name());
        $this->hydrator = $this->form->setHydrator(new DoctrineObject($this->em, '\\'.$name));
        $this->form->bind($class);
        $el = new Element\Hidden('hidden');
        $this->form->add($el);
    }

    public function add($t, $arr)
    {
        $name = $t;
        if (isset($arr['name'])) $name = $arr['name'];
        $t = "Zend\\Form\\Element\\" . $t;
        $this->el = new $t();
        $this->el->setName($name);
        if (isset($arr['label'])) $this->el->setLabel($arr['label']);
        if (isset($arr['attr'])){
            $this->el->setAttributes($arr['attr']);
        }
        if (isset($arr['class'])){
            $this->el->setAttribute('class', $arr['class']);
//            $opt['option_attributes'] = ['class' => $arr['class']];
        }
//        if ($t == 'submit') $this->el->
        $this->form->add($this->el);
    }
    public function addDoctrine($arr)
    {
        $opt = [
            'object_manager' => $this->em,
            'target_class'   => $arr['target'],
        ];
        $prop = $arr['prop'];
        if (isset($prop) && !is_array($prop)) $opt['property'] = $arr['prop'];
        else if (isset($arr) && is_array($prop)) $opt['label_generator'] = function ($targetEntity) use ($prop){
            $str = '';
            foreach ($prop as $item) {
                if(is_array($item)){
                    $ob = call_user_func([$targetEntity, 'get' . $item[0]]);
                    $ob = call_user_func([$ob, 'get' . $item[1]]);
                }
                else $ob = call_user_func([$targetEntity, 'get' . $item]);
                $str .= $ob . ' - ';
            }
             return $str;
        };
        if (isset($arr['class'])){
//            $opt['option_attributes'] = ['class' => $arr['class']];
        }
        if (isset($arr['criteria'])){
            $opt['is_method'] = true;
            $opt['find_method'] = [
                'name'   => 'findBy',
                'params' => [
                    'criteria' => [$arr['criteria'][0] => $arr['criteria'][1]],
                ],
            ];
        }
        $this->form->add([
            'type' => 'DoctrineModule\\Form\\Element\\'. $arr['type'],
            'name' => $arr['name'],
            'options' => $opt,
        ]);
        $this->el = $this->form->get($arr['name']);
        if (isset($arr['label']))
            $this->el->setLabel($arr['label']);
        if (isset($arr['class'])){
            $this->el->setAttribute('class', $arr['class']);
//            $opt['option_attributes'] = ['class' => $arr['class']];
        }
    }
    public function delete($name)
    {
        $this->form->remove($name);
    }

    /**
     * @return Form
     */
    public function getForm(){
        return $this->form;
    }

    public function setData($arr){
        $this->form->setData($arr);
    }

    /**
     * @param $act - контроллер действие
     */
    public function setAction($act)
    {
        $this->form->setAttribute('action',$act);
    }

    /**
     * @param $e - element
     * @param $t - type
     * @param $v - value
     */
    public function setValue($e, $t ,$v)
    {
        $this->form->get($e)->setAttribute($t, $v);
    }

    public function setElemPar($e, $name, $value)
    {
        $elem = $this->form->get($e);
        call_user_func_array([$elem, 'set' . $name],[$value]);
    }
    public function getEl($name)
    {
        return $this->form->get($name);
    }

    public function customRender()
    {
        
    }

    /**
     * @param $arr - array
     * @param  $rend
     * @param string $title
     * @param string $class
     */
    public function render($title ,$arr, $rend, $class = "form-group")
    {
        $this->r = $rend;
        $this->helper = $this->r->form();
        $this->form->setAttribute('class', $arr['form']);
        $this->elements[] = $this->helper->openTag($this->form);
        $this->elements[] = "<fieldset><legend>{$title}</legend>";
        $els = $this->form->getElements();
        foreach ($els as $el) {
            $this->elements[] = "<div class=\"{$class}\">";
            $this->el = $el;
            $this->attr = $this->el->getAttributes();
            if($this->el->getLabel()){
                $this->el->setLabelAttributes(['class', $arr['label']]);
                $label = $this->el->getLabel();
                if (isset($this->attr['required'])){
                    $this->el->setLabel($this->el->getLabel().' *');
                }
                $label = "<label class='{$arr['label']}'>{$label}</label>";
            } else { $label = '';}
            $type = isset($this->attr['type']) ? $this->attr['type'] : '';
            $fe = '';
            switch ($type){
                case 'date':
                    if (empty($this->el->getValue()))
                        $this->el->setValue(date('Y-m-d'));
                    $fe = $this->r->formDate($this->el);
                    break;
                case 'select':
                    $fe = $this->r->formSelect($this->el);
                    break;
                default:
                    $fe = $this->r->formElement($this->el);
                    break;
            }
            $this->elements[] = $label;
            $this->elements[] = "<div class={$arr['e']}>{$fe}</div>";
            $this->elements[] = '</div>';
        }
        $this->elements[] = '</<fieldset>';

        $this->elements[] = $this->helper->closeTag();
    }
    public function pr($start, $end){
        if (isset($start)) echo $start;
        foreach ($this->elements as $element) {
            echo $element;
        }
        if (isset($end)) echo $end;
    }
}