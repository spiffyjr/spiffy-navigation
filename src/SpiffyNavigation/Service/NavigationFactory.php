<?php

namespace SpiffyNavigation\Service;

use RuntimeException;
use SpiffyNavigation\ContainerFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NavigationFactory implements FactoryInterface
{
    /**
     * Creates the Navigation service.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Navigation
     * @throws RuntimeException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Configuration');

        if (!isset($config['spiffynavigation'])) {
            throw new RuntimeException('Missing `spiffynavigation` configuration key');
        }

        $config     = $config['spiffynavigation'];
        $navigation = new Navigation();

        foreach((array) $config['containers'] as $containerName => $container) {
            $navigation->addContainer($containerName, ContainerFactory::create($container));
        }

        $application = $serviceLocator->get('Application');
        $routeMatch  = $application->getMvcEvent()->getRouteMatch();
        $router      = $application->getMvcEvent()->getRouter();

        $navigation->setRouteMatch($routeMatch);
        $navigation->setRouter($router);

        return $navigation;
    }
}