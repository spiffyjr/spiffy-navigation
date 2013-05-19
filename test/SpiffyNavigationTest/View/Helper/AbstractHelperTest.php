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

    public function testSetPartial()
    {
        $helper = new SimpleHelper($this->nav);
        $helper->setPartial('partials/test.phtml');

        $this->assertEquals('partials/test.phtml', $helper->getPartial());
    }

    public function testSetInvalidPartialIgnored()
    {
        $helper = new SimpleHelper($this->nav);
        $helper->setPartial(new \stdClass());

        $this->assertNull($helper->getPartial());
    }

    public function testResetPartialToNull()
    {
        $helper = new SimpleHelper($this->nav);

        $helper->setPartial('partials/test.phtml');
        $helper->setPartial(null);

        $this->assertNull($helper->getPartial());
    }
}
