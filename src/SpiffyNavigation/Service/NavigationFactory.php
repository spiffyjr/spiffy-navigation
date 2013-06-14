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
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return Navigation
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \SpiffyNavigation\Options\ModuleOptions $options */
        $options    = $serviceLocator->get('SpiffyNavigation\Options\ModuleOptions');
        $navigation = new Navigation();

        foreach($options->getContainers() as $containerName => $container) {
            if (is_string($container)) {
                if ($serviceLocator->has($container)) {
                    $container = $serviceLocator->get($container);
                } else {
                    $container = new $container();
                }
            } else if (is_array($container)) {
                $container = ContainerFactory::create($container);
            }

            $navigation->addContainer($containerName, $container);
        }

        foreach($options->getListeners() as $priority => $listener) {
            if (is_string($listener)) {
                if ($serviceLocator->has($listener)) {
                    $listener = $serviceLocator->get($listener);
                } else {
                    $listener = new $listener();
                }
            }

            if (is_numeric($priority)) {
                $navigation->getEventManager()->attachAggregate($listener, $priority);
            } else {
                $navigation->getEventManager()->attachAggregate($listener);
            }
        }

        $application = $serviceLocator->get('Application');
        $routeMatch  = $application->getMvcEvent()->getRouteMatch();
        $router      = $application->getMvcEvent()->getRouter();

        $navigation->setRouteMatch($routeMatch);
        $navigation->setRouter($router);

        return $navigation;
    }
}