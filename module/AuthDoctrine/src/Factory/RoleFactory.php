<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 03.11.2017
 * Time: 14:28
 */

namespace AuthDoctrine\Factory;


use AuthDoctrine\Controller\RoleController;
use AuthDoctrine\Service\RoleManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class RoleFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $roleManager = $container->get(RoleManager::class);
        return new RoleController($entityManager, $roleManager);
    }
}