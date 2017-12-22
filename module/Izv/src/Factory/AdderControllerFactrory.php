<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 05.12.2017
 * Time: 12:26
 */

namespace Izv\Factory;


use Interop\Container\ContainerInterface;
use Izv\Controller\IndexController;
use Zend\ServiceManager\Factory\FactoryInterface;

class AdderControllerFactrory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $auth = $container->get('doctrine.authenticationservice.orm_default');
        $config = $container->get('configuration');
        $path['Path'] = $config['PathIzv'];
        $config = $config['models'];
        $config = array_merge($config, $path);
        return new IndexController($entityManager, $auth, $config);
    }
}