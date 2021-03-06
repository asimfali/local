<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 25.12.2017
 * Time: 11:39
 */

namespace Tech\Factory;


use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Tech\Controller\IndexController;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface
{

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $em = $container->get('doctrine.entitymanager.orm_default');
        $auth = $container->get('doctrine.authenticationservice.orm_default');
        $config = $container->get('configuration');
        $path['Path'] = $config['PathTech'];
        $config = $config['models'];
        $config = array_merge($config, $path);
        return new IndexController($em, $auth, $config);
    }
}