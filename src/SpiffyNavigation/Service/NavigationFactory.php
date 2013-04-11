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
        $sortKey = (isset($config['containers']['sortKey'])) ? $config['containers']['sortKey'] : 'order';

        foreach((array) $config['containers'] as $containerName => $container) {
            $container = $this->sortContainer($container, $sortKey);
            $navigation->addContainer($containerName, ContainerFactory::create($container));
        }

        $application = $serviceLocator->get('Application');
        $routeMatch  = $application->getMvcEvent()->getRouteMatch();
        $router      = $application->getMvcEvent()->getRouter();

        $navigation->setRouteMatch($routeMatch);
        $navigation->setRouter($router);

        return $navigation;
    }

    /**
     * set order on the container pages
     * 
     * @param array  $container Container
     * @param String $keyName   Name of the key to sort
     * 
     * @return array
     */
    public function sortContainer($container, $keyName)
    {
        $return = array();
        foreach ($container as $page) {
            if (isset($page[$keyName])) {
                $return[$page[$keyName]] = $page;
            } else {
                $return[] = $page;
            }
        }
        ksort($return);
        return array_values($return);
    }
}