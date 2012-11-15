<?php

namespace SpiffyNavigationTest\View\Helper;

use ReflectionClass;
use SpiffyNavigationTest\AbstractTest;
use SpiffyNavigationTest\View\Helper\Asset\SimpleHelper;

class AbstractHelperTest extends AbstractTest
{
    public function testInvokeSetsContainer()
    {
        $helper = new SimpleHelper($this->nav);
        $helper->__invoke('container1');

        $reflClass = new ReflectionClass($helper);
        $container = $reflClass->getProperty('container');
        $container->setAccessible(true);
        $container = $container->getValue($helper);

        $this->assertEquals($this->container1, $container);
    }
}
