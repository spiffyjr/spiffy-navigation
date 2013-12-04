<?php

namespace SpiffyNavigationTest\Service;

use SpiffyNavigation\ModuleOptions;
use SpiffyNavigation\Service\NavigationFactory;
use SpiffyNavigationTest\AbstractTest;

class NavigationFactoryTest extends AbstractTest
{
    public function testCreateContainersFromConfig()
    {
        $config = include __DIR__ . '/../_files/config/module.config.php';
        $config['spiffy_navigation']['containers']['container1'] = include __DIR__ . '/../_files/config/container1.php';
        $config['spiffy_navigation']['containers']['container2'] = include __DIR__ . '/../_files/config/container2.php';
        $this->serviceManager->setService(
            'SpiffyNavigation\ModuleOptions',
            new ModuleOptions($config['spiffy_navigation'])
        );
        $this->serviceManager->setService('Configuration', $config);

        $factory = new NavigationFactory();
        $service = $factory->createService($this->serviceManager);
        $this->assertInstanceOf('SpiffyNavigation\Service\Navigation', $service);
        $this->assertCount(2, $service->getContainers());
    }

    public function testNavigationServiceIsReturned()
    {
        $config = include __DIR__ . '/../_files/config/module.config.php';
        $this->serviceManager->setService('SpiffyNavigation\ModuleOptions', new ModuleOptions());
        $this->serviceManager->setService('Configuration', $config);

        $factory = new NavigationFactory();
        $service = $factory->createService($this->serviceManager);
        $this->assertInstanceOf('SpiffyNavigation\Service\Navigation', $service);
    }
}