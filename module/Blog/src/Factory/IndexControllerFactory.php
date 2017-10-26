<?php

/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 25.10.2017
 * Time: 10:15
 */
namespace Blog\Factory;

use Blog\Controller\IndexController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        return new IndexController($entityManager);
    }
}