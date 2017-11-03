<?php

/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 26.10.2017
 * Time: 11:24
 */
namespace AuthDoctrine;

use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}