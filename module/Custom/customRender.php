<?php
/**
 * Created by PhpStorm.
 * User: simanov
 * Date: 22.12.2017
 * Time: 13:57
 */

namespace Custom;


use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\AggregateResolver;
use Zend\View\Resolver\RelativeFallbackResolver;
use Zend\View\Resolver\TemplateMapResolver;
use Zend\View\Resolver\TemplatePathStack;

class CustomRender
{
    /**
     * @var PhpRenderer $r
     */
    public $r;
    /**
     * @var AggregateResolver $res
     */
    public $res;
    /**
     * @var ViewModel $model
     */
    public $model;

    public function __construct($tmr, $tsr)
    {
        $this->r = new PhpRenderer();
        $this->res = new AggregateResolver();
        $this->r->setResolver($this->res);
        $map = new TemplateMapResolver($tmr);
        $stack = new TemplatePathStack([
            'script_paths' => $tsr
        ]);
        $this->res->attach($map)->attach($stack)->attach(new RelativeFallbackResolver($map))
            ->attach(new RelativeFallbackResolver($stack));
    }
    public function render($name, $arr)
    {
        if (!isset($this->model)) $this->model = new ViewModel();
        if (get_class($name) === 'Zend\\View\\Model\\ViewModel'){
            $this->model = $name;
        } else {
            $this->model->setVariables($arr);
            $this->model->setTemplate($name);
        }
        return $this->r->render($this->model);
    }
}