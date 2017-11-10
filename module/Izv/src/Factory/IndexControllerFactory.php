<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 10.11.2017
 * Time: 13:17
 */

namespace Izv\Factory;


use Interop\Container\ContainerInterface;
use Izv\Controller\IndexController;
use Zend\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        return new IndexController($entityManager);
    }
}