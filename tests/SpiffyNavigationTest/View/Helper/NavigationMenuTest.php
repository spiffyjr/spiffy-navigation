<?php

namespace SpiffyNavigationTest\View\Helper;

use ReflectionClass;
use SpiffyNavigation\Page\Page;
use SpiffyNavigationTest\AbstractTest;
use SpiffyNavigation\ContainerFactory;

class NavigationMenuTest extends AbstractTest
{
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
        $page->setProperties(array('label' => 'Foo', 'uri' => 'http://www.google.com', 'invalid' => 'attribute'));

        $reflectionClass = new ReflectionClass($this->helper);
        $htmlify = $reflectionClass->getMethod('htmlify');
        $htmlify->setAccessible(true);

        $this->assertEquals('<a href="http://www.google.com">Foo</a>', $htmlify->invoke($this->helper, $page));
    }

    public function testHtmlifyForPageWithHref()
    {
        $page = new Page();
        $page->setProperties(array('label' => 'Foo', 'uri' => 'http://www.google.com'));

        $reflectionClass = new ReflectionClass($this->helper);
        $htmlify = $reflectionClass->getMethod('htmlify');
        $htmlify->setAccessible(true);

        $this->assertEquals('<a href="http://www.google.com">Foo</a>', $htmlify->invoke($this->helper, $page));
    }

    public function testHtmlifyForPageWithNoHref()
    {
        $page = new Page();
        $page->setProperties(array('label' => 'Foo'));

        $reflectionClass = new ReflectionClass($this->helper);
        $htmlify = $reflectionClass->getMethod('htmlify');
        $htmlify->setAccessible(true);

        $this->assertEquals('<span>Foo</span>', $htmlify->invoke($this->helper, $page));
    }

    /**
     * Use case: We show a first menu with all main sections like 'test' and 'foo'
     *
     */
    public function testMaxDepth()
    {
        $this->setUpRouteForTreeMenu();
        $this->assertEquals(
            $this->asset('expected/menu3MaxDepth.html'),
            $this->helper->renderMenu(
                'container3',
                array('maxDepth' => 0)
            )
        );
    }

    /**
     * Use case: We want to show the 'submenu' of the active menu 'foo'
     */
    public function testMinDepth()
    {
        $this->setUpRouteForTreeMenu();
        $this->assertEquals(
            $this->asset('expected/menu3MinDepth.html'),
            $this->helper->renderMenu(
                'container3',
                array('minDepth' => 0)
            )
        );
    }

    /**
     * Current route defined to match foo/list, so we get two <li class="active">
     */
    public function testActiveClass()
    {
        $this->setUpRouteForTreeMenu();
        $this->assertEquals(
            $this->asset('expected/menu3ActiveClass.html'),
            $this->helper->renderMenu(
                'container3',
                array('activeClass' => 'myActiveClass')
            )
        );
    }
}