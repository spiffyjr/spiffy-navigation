<?php

namespace SpiffyNavigationTest\View\Helper;

use ReflectionClass;
use SpiffyNavigation\Listener\RbacListener;
use SpiffyNavigation\Page\Page;
use SpiffyNavigationTest\AbstractTest;
use Zend\Permissions\Rbac\Rbac;

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

    public function testRenderMenuOrdering()
    {
        $this->assertEquals($this->asset('expected/menu4.html'), $this->helper->renderMenu('container4'));
    }

    public function testIsAllowedRbac()
    {
        $rbac = new Rbac();

        $this->nav->getEventManager()->attach(new RbacListener($rbac));
        $this->assertEquals($this->asset('expected/menu3RbacNone.html'), $this->helper->renderMenu('container3'));

        $rbac->addRole('foo');
        $rbac->getRole('foo')->addPermission('child2');
        $this->assertEquals($this->asset('expected/menu3RbacChild2.html'), $this->helper->renderMenu('container3'));

        $rbac->getRole('foo')->addPermission('child3-add');
        $this->assertEquals($this->asset('expected/menu3RbacAll.html'), $this->helper->renderMenu('container3'));
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
