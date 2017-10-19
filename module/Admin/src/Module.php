<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 18.10.2017
 * Time: 12:21
 */

namespace Admin;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}