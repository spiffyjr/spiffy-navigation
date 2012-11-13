<?php

namespace SpiffyNavigation\Service;


use SpiffyNavigation\Page\Page;use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NavigationFactory implements FactoryInterface
{
    /**
     * Creates the Navigation service.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config     = $serviceLocator->get('Configuration');
        $config     = $config['spiffynavigation'];
        $navigation = new Navigation();

        foreach((array) $config['containers'] as $containerName => $container) {
            $navigation->addContainer($containerName, Page::factory($container));
        }

        $application = $serviceLocator->get('Application');
        $routeMatch  = $application->getMvcEvent()->getRouteMatch();
        $router      = $application->getMvcEvent()->getRouter();

        $navigation->setRouteMatch($routeMatch);
        $navigation->setRouter($router);

        return $navigation;
    }
}