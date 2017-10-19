<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 18.10.2017
 * Time: 12:22
 */

namespace Admin\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
}