<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 23.10.2017
 * Time: 12:49
 */
namespace Admin\Factory;

use Admin\Controller\ArticleController;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class ArticleControllerFactory implements FactoryInterface{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        return new ArticleController($entityManager);
    }
}