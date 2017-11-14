<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 13.11.2017
 * Time: 10:51
 */

namespace Izv;


use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}