<?php

namespace SpiffyNavigationTest\Service;

use SpiffyNavigation\Service\NavigationFactory;
use SpiffyNavigationTest\AbstractTest;

class NavigationFactoryTest extends AbstractTest
{
    public function testNavigationServiceThrowsExceptionWithMissingConfigurationKey()
    {
        $this->setExpectedException('RuntimeException', 'Missing `spiffynavigation` configuration key');

        $factory = new NavigationFactory();
        $factory->createService($this->serviceManager);
    }

    public function testCreateContainersFromConfig()
    {
        $config = include 'SpiffyNavigationTest/_files/config/module.config.php';
        $config['spiffynavigation']['containers']['container1'] = include 'SpiffyNavigationTest/_files/config/container1.php';
        $config['spiffynavigation']['containers']['container2'] = include 'SpiffyNavigationTest/_files/config/container2.php';
        $this->serviceManager->setService('Configuration', $config);

        $factory = new NavigationFactory();
        $service = $factory->createService($this->serviceManager);
        $this->assertInstanceOf('SpiffyNavigation\Service\Navigation', $service);
        $this->assertCount(2, $service->getContainers());
    }

    public function testNavigationServiceIsReturned()
    {
        $config = include 'SpiffyNavigationTest/_files/config/module.config.php';
        $this->serviceManager->setService('Configuration', $config);

        $factory = new NavigationFactory();
        $service = $factory->createService($this->serviceManager);
        $this->assertInstanceOf('SpiffyNavigation\Service\Navigation', $service);
    }

    public function testNavigationServiceSortContainerFunction()
    {
        $currentConfig = include 'SpiffyNavigationTest/_files/config/container2.php';
        $expectedConfig = include 'SpiffyNavigationTest/_files/expected/container-ordained.php';
        $factory = new NavigationFactory();
        $result = $factory->sortContainer($currentConfig);

        $this->assertEquals($expectedConfig, $result);
    }
}