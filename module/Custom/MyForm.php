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
     * MyForm constructor.
     * @param $class
     * @param EntityManager $entityManager
     */
    public function __construct($class ,EntityManager $entityManager)
    {
        $this->em = $entityManager;
        $this->ab = new AnnotationBuilder($this->em);
        $name = get_class($class);
        $this->form = $this->ab->createForm(new $name());
        $this->form->setHydrator(new DoctrineObject($this->em, '\\'.$name));
        $this->form->bind($class);
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

    /**
     * @param $arr - array
     */
    public function render($title ,$arr, $rend)
    {
        $this->r = $rend;
        $this->helper = $this->r->form();
        $this->elements[] = $this->helper->openTag($this->form);
        $this->elements[] = "<fieldset><legend>{$title}</legend>";
        $els = $this->form->getElements();
        foreach ($els as $el) {
            $this->elements[] = '<div class="form-group">';
            $this->el = $el;
            $this->attr = $this->el->getAttributes();
            if($this->el->getLabel()){
                $this->el->setLabelAttributes(['class', $arr['label']]);
                $label = $this->el->getLabel();
                if (isset($this->attr['required'])){
                    $this->el->setLabel($this->el->getLabel().' *');
                }
                $label = "<label class={$arr['label']}>{$label}</label>";
            }
            $type = isset($this->attr['type']) ? $this->attr['type'] : '';
            $fe = '';
            switch ($type){
//                case 'text':
////                    $fe = $this->r->
//                    break;
                default:
                    $fe = $this->r->formElement($this->el);
                    break;
            }
            $this->elements[] = "{$label} <div class={$arr['e']}>{$fe}</div>";
        }
    }
}