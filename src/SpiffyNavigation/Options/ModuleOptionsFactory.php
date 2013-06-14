<?php

namespace SpiffyNavigation\Options;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ModuleOptionsFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @throws \RuntimeException
     * @return ModuleOptions
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Configuration');

        if (!isset($config['spiffy_navigation'])) {
            throw new \RuntimeException('Missing `spiffy_navigation` configuration key');
        }

        $config = $config['spiffy_navigation'];
        return new ModuleOptions($config);
    }
}