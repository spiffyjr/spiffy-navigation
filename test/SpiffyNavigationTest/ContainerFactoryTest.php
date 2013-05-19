<?php

namespace SpiffyNavigationTest\Page;

use PHPUnit_Framework_TestCase;
use SpiffyNavigation\ContainerFactory;

class ContainerFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testCreateContainerFromSpec()
    {
        $config     = include 'SpiffyNavigationTest/_files/config/container1.php';
        $container1 = ContainerFactory::create($config);

        $this->assertInstanceOf('SpiffyNavigation\Container', $container1);
    }
}
