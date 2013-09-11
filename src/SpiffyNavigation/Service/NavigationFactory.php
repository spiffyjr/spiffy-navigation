<?php

namespace SpiffyNavigation\Service;

use SpiffyNavigation\ContainerFactory;
use SpiffyNavigation\Provider\ArrayProvider;
use SpiffyNavigation\Provider\ProviderFactory;
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
        /** @var \SpiffyNavigation\ModuleOptions $options */
        $options    = $serviceLocator->get('SpiffyNavigation\ModuleOptions');
        $navigation = new Navigation();
        $providers  = $options->getProviders();

        foreach($options->getContainers() as $containerName => $container) {
            if (is_string($container)) {
                if (isset($providers[$container])) {
                    $container = ProviderFactory::create($providers[$container])->getContainer();
                } elseif ($serviceLocator->has($container)) {
                    $container = $serviceLocator->get($container);
                } else {
                    $container = new $container();
                }
            } elseif (is_array($container)) {
                $provider  = new ArrayProvider();
                $provider->setOptions(array(
                    'config' => $container
                ));
                $container = $provider->getContainer();
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