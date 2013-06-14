<?php

namespace SpiffyNavigationTest\Service;

use SpiffyNavigation\Options\ModuleOptions;
use SpiffyNavigation\Service\NavigationFactory;
use SpiffyNavigationTest\AbstractTest;

class NavigationFactoryTest extends AbstractTest
{
    public function testCreateContainersFromConfig()
    {
        $config = include 'SpiffyNavigationTest/_files/config/module.config.php';
        $config['spiffy_navigation']['containers']['container1'] = include 'SpiffyNavigationTest/_files/config/container1.php';
        $config['spiffy_navigation']['containers']['container2'] = include 'SpiffyNavigationTest/_files/config/container2.php';
        $this->serviceManager->setService('SpiffyNavigation\Options\ModuleOptions', new ModuleOptions($config['spiffy_navigation']));
        $this->serviceManager->setService('Configuration', $config);

        $factory = new NavigationFactory();
        $service = $factory->createService($this->serviceManager);
        $this->assertInstanceOf('SpiffyNavigation\Service\Navigation', $service);
        $this->assertCount(2, $service->getContainers());
    }

    public function testNavigationServiceIsReturned()
    {
        $config = include 'SpiffyNavigationTest/_files/config/module.config.php';
        $this->serviceManager->setService('SpiffyNavigation\Options\ModuleOptions', new ModuleOptions());
        $this->serviceManager->setService('Configuration', $config);

        $factory = new NavigationFactory();
        $service = $factory->createService($this->serviceManager);
        $this->assertInstanceOf('SpiffyNavigation\Service\Navigation', $service);
    }
}