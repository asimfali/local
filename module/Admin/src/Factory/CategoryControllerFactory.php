<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 19.10.2017
 * Time: 16:18
 */
namespace Admin\Factory;

use Admin\Controller\CategoryController;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class CategoryControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $auth = $container->get('doctrine.authenticationservice.orm_default');
        return new CategoryController($entityManager, $auth);
    }
//
//    public function createService(ServiceLocatorInterface $serviceLocator){
//        $real = $serviceLocator->getServiceLocator();
//
//    }
}