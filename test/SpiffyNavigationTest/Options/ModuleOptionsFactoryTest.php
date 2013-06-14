<?php

namespace SpiffyNavigationTest\Options;

use SpiffyNavigation\Options\ModuleOptionsFactory;
use SpiffyNavigationTest\AbstractTest;

class ModuleOptionsFactoryTest extends AbstractTest
{
    public function testInstanceReturned()
    {
        $this->setExpectedException('RuntimeException', 'Missing `spiffy_navigation` configuration key');

        $factory = new ModuleOptionsFactory();
        $factory->createService($this->serviceManager);
    }

    public function testCreateContainersFromConfig()
    {
        $config = include 'SpiffyNavigationTest/_files/config/module.config.php';
        $config['spiffy_navigation']['containers'] = array('1', '2', '3');
        $config['spiffy_navigation']['listeners'] = array('abc');
        $this->serviceManager->setService('Configuration', $config);

        $factory = new ModuleOptionsFactory();
        $service = $factory->createService($this->serviceManager);
        $this->assertInstanceOf('SpiffyNavigation\Options\ModuleOptions', $service);
        $this->assertEquals($config['spiffy_navigation']['containers'], $service->getContainers());
        $this->assertEquals($config['spiffy_navigation']['listeners'], $service->getListeners());
    }
}