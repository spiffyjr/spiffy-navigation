<?php

namespace SpiffyNavigationTest\View\Helper;

use ReflectionClass;
use SpiffyNavigation\Page\Page;
use SpiffyNavigationTest\AbstractTest;

class NavigationMenuTest extends AbstractTest
{
    /**
     * @var \SpiffyNavigation\View\Helper\NavigationMenu
     */
    protected $helper;

    /**
     * @var string
     */
    protected $helperName = 'SpiffyNavigation\View\Helper\NavigationMenu';

    public function testRenderMenu()
    {
        $this->assertEquals($this->asset('expected/menu1.html'), $this->helper->renderMenu('container1'));
    }

    public function testHtmlifyIgnoresInvalidAttributes()
    {
        $page = new Page();
        $page->setOptions(array('label' => 'Foo', 'uri' => 'http://www.google.com', 'invalid' => 'attribute'));

        $reflectionClass = new ReflectionClass($this->helper);
        $htmlify = $reflectionClass->getMethod('htmlify');
        $htmlify->setAccessible(true);

        $this->assertEquals('<a href="http://www.google.com">Foo</a>', $htmlify->invoke($this->helper, $page));
    }

    public function testHtmlifyForPageWithHref()
    {
        $page = new Page();
        $page->setOptions(array('label' => 'Foo', 'uri' => 'http://www.google.com'));

        $reflectionClass = new ReflectionClass($this->helper);
        $htmlify = $reflectionClass->getMethod('htmlify');
        $htmlify->setAccessible(true);

        $this->assertEquals('<a href="http://www.google.com">Foo</a>', $htmlify->invoke($this->helper, $page));
    }

    public function testHtmlifyForPageWithNoHref()
    {
        $page = new Page();
        $page->setOptions(array('label' => 'Foo'));

        $reflectionClass = new ReflectionClass($this->helper);
        $htmlify = $reflectionClass->getMethod('htmlify');
        $htmlify->setAccessible(true);

        $this->assertEquals('<span>Foo</span>', $htmlify->invoke($this->helper, $page));
    }

    public function testRenderPartial()
    {
        $this->assertEquals($this->asset('expected/partial1.html'), $this->helper->renderPartial('container1', 'partial1.phtml'));
    }
}