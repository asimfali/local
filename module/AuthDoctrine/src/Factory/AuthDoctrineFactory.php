<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 26.10.2017
 * Time: 13:45
 */

namespace AuthDoctrine\Factory;

use AuthDoctrine\Controller\IndexController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class AuthDoctrineFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $em = $container->get('doctrine.entitymanager.orm_default');
        $auth = $container->get('doctrine.authenticationservice.orm_default');
        return new IndexController($em, $auth);
    }
}