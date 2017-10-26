<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 26.10.2017
 * Time: 8:54
 */

namespace Admin\Factory;


use Admin\Controller\CommentController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class CommentControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        return new CommentController($entityManager);
    }
}