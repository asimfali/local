<?php

/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 24.10.2017
 * Time: 14:17
 */
namespace Admin\Lib;
use Zend\Navigation\Service\DefaultNavigationFactory;

class AdminNavigationFactory extends DefaultNavigationFactory
{
    protected function getName()
    {
        return 'admin_navigation';
    }
}