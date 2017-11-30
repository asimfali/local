<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 29.11.2017
 * Time: 14:09
 */

namespace Custom;


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

    public function __construct(PhpRenderer $phpRenderer, $name)
    {
        $this->r = $phpRenderer;
        $this->cont = $this->r->placeholder($name);
        $this->cont->captureStart();
    }
    public function addTag($str)
    {
        return "<div>" . $str . "</div>";
    }
    public function addDiv($name,$val)
    {
        $this->cont->append($this->r->partial($name, $val));
    }
    public function setClass($class)
    {
        $this->cont->setPrefix("<div class=\"{$class}\">");
        $this->cont->setPostfix("</div>");
    }
    public function pr()
    {
        $this->cont->captureEnd();
        return $this->cont->toString();
    }
}