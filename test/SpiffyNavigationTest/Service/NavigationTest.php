<?php

namespace SpiffyNavigationTest\Service;

use ReflectionClass;
use SpiffyNavigation\Listener\RbacListener;
use SpiffyNavigationTest\AbstractTest;
use SpiffyNavigation\Page\Page;
use SpiffyNavigation\Service\Navigation;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\Mvc\Router\Http\Regex as RegexRoute;
use Zend\Permissions\Rbac\Rbac;

class NavigationTest extends AbstractTest
{
    public function testIsActiveCache()
    {
        $routeMatch = new RouteMatch(array());
        $routeMatch->setMatchedRouteName('test');

        $router = new TreeRouteStack();
        $router->addRoute('test', new Literal('/foo-bar'));

        $page = new Page();
        $page->setOptions(array('route' => 'test'));

        $navigation = new Navigation();
        $navigation->setRouteMatch($routeMatch);

        // Warm cache
        $hash   = spl_object_hash($page);
        $result = $navigation->isActive($page);

        $reflClass = new ReflectionClass($navigation);
        $isActiveCache = $reflClass->getProperty('isActiveCache');
        $isActiveCache->setAccessible(true);

        $isActiveCache = $isActiveCache->getValue($navigation);

        $this->assertCount(1, $isActiveCache);
        $this->assertArrayHasKey($hash, $isActiveCache);
        $this->assertEquals($result, $isActiveCache[$hash]);
    }

    public function testIsActiveIsFalseForNewPage()
    {
        $navigation = new Navigation();
        $page       = new Page();

        $this->assertFalse($navigation->isActive($page));
    }

    public function testIsActiveCacheIsClearedWhenRecursionIsModified()
    {
        $routeMatch = new RouteMatch(array());
        $routeMatch->setMatchedRouteName('test');

        $router = new TreeRouteStack();
        $router->addRoute('test', new Literal('/foo-bar'));

        $page = new Page();
        $page->setOptions(array('route' => 'test'));

        $navigation = new Navigation();
        $navigation->setRouteMatch($routeMatch);

        // Warm cache
        $navigation->isActive($page);
        $navigation->setIsActiveRecursion(!$navigation->getIsActiveRecursion());

        $reflClass = new ReflectionClass($navigation);
        $isActiveCache = $reflClass->getProperty('isActiveCache');
        $isActiveCache->setAccessible(true);

        $isActiveCache = $isActiveCache->getValue($navigation);

        $this->assertCount(0, $isActiveCache);
    }

    public function testIsActiveWithRecursion()
    {
        $routeMatch = new RouteMatch(array());
        $routeMatch->setMatchedRouteName('test');

        $router = new TreeRouteStack();
        $router->addRoute('test', new Literal('/foo-bar'));

        $child = new Page();
        $child->setOptions(array('route' => 'test'));

        $page = new Page();
        $page->addChild($child);

        $navigation = new Navigation();
        $navigation->setRouteMatch($routeMatch);

        $this->assertTrue($navigation->isActive($page));
        $this->assertTrue($navigation->isActive($child));
    }

    public function testIsActiveWithRecursionDisabled()
    {
        $routeMatch = new RouteMatch(array());
        $routeMatch->setMatchedRouteName('test');

        $router = new TreeRouteStack();
        $router->addRoute('test', new Literal('/foo-bar'));

        $child = new Page();
        $child->setOptions(array('route' => 'test'));

        $page = new Page();
        $page->addChild($child);

        $navigation = new Navigation();
        $navigation->setRouteMatch($routeMatch);
        $navigation->setIsActiveRecursion(false);

        $this->assertFalse($navigation->isActive($page));
        $this->assertTrue($navigation->isActive($child));
    }

    public function testIsActive()
    {
        $routeMatch = new RouteMatch(array());
        $routeMatch->setMatchedRouteName('test');

        $router = new TreeRouteStack();
        $router->addRoute('test', new Literal('/foo-bar'));

        $page = new Page();
        $page->setOptions(array('route' => 'test'));

        $navigation = new Navigation();
        $navigation->setRouteMatch($routeMatch);

        $this->assertTrue($navigation->isActive($page));
    }

    public function testGetContainerWithInvalidNameThrowsException()
    {
        $this->setExpectedException('InvalidArgumentException');

        $navigation = new Navigation();
        $navigation->getContainer('foo');
    }

    public function testHasContainer()
    {
        $this->assertTrue($this->nav->hasContainer('container1'));
        $this->assertFalse($this->nav->hasContainer('foo'));
    }

    public function testAddContainerThrowsExceptionOnDuplicateName()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->nav->addContainer('container1', $this->nav->getContainer('container1'));
    }

    public function testClearContainerResetsContainers()
    {
        $nav = clone $this->nav;
        $nav->clearContainers();
        $this->assertCount(0, $nav->getContainers());
    }

    public function testSetContainerSetsANewContainer()
    {
        $navigation = new Navigation();
        $page       = new Page();

        $navigation->addContainer('test', $page);
        $this->assertCount(1, $navigation->getContainers());
    }

    public function testGetContainerReturnsAllContainers()
    {
        $containers = $this->nav->getContainers();
        $this->assertEquals($this->container1, $containers['container1']);
        $this->assertEquals($this->container2, $containers['container2']);
    }

    public function testRemoveContainerWithInvalidNameThrowsException()
    {
        $this->setExpectedException('InvalidArgumentException');

        $navigation = new Navigation();
        $navigation->removeContainer('foo');
    }

    public function testRemoveContainer()
    {
        $nav = clone $this->nav;
        $nav->removeContainer('container1');

        $containers = $nav->getContainers();
        $this->assertCount(3, $containers);
        $this->assertEquals($this->container2, $containers['container2']);
    }

    public function testGetHref()
    {
        $page = $this->nav->getContainer('container1')->findOneByOption('name', 'child1');
        $this->assertEquals('http://www.child1.com', $this->nav->getHref($page));
    }

    public function testGetHrefWithFragment()
    {
        $page = $this->nav->getContainer('container1')->findOneByOption('name', 'child1');
        $page->setOption('fragment', 'test');

        $this->assertEquals('http://www.child1.com#test', $this->nav->getHref($page));
    }

    public function testHrefCache()
    {
        $navigation = new Navigation();

        $page = new Page();
        $page->setOptions(array('uri' => 'www.foobar.com'));

        // Warm cache
        $href = $navigation->getHref($page);
        $hash = spl_object_hash($page);

        $reflClass = new ReflectionClass($navigation);
        $hrefCache = $reflClass->getProperty('hrefCache');
        $hrefCache->setAccessible(true);

        $hrefCache = $hrefCache->getValue($navigation);

        $this->assertCount(1, $hrefCache);
        $this->assertArrayHasKey($hash, $hrefCache);
        $this->assertEquals($href, $hrefCache[$hash]);
    }

    public function testGetHrefThrowsExceptionWithMissingRouter()
    {
        $this->setExpectedException('RuntimeException', 'Cannot construct mvc href with no router');

        $navigation = new Navigation();

        $page = new Page();
        $page->setOptions(array('route' => 'test'));

        $navigation->getHref($page);
    }

    public function testGetHrefFromRouteWithParams()
    {
        $route  = new RegexRoute('/foo/edit/(?<id>\d+)', '/foo/edit/%id%');
        $router = new TreeRouteStack();
        $router->addRoute('test', $route);

        $navigation = new Navigation();
        $navigation->setRouter($router);

        $page = new Page();
        $page->setOptions(array('route' => 'test', 'params' => array('id' => 1234)));

        $this->assertEquals('/foo/edit/1234', $navigation->getHref($page));
    }

    public function testGetHrefFromRoute()
    {
        $route  = new Literal('/foo-bar');
        $router = new TreeRouteStack();
        $router->addRoute('test', $route);

        $navigation = new Navigation();
        $navigation->setRouter($router);

        $page = new Page();
        $page->setOptions(array('route' => 'test'));

        $this->assertEquals('/foo-bar', $navigation->getHref($page));
    }

    public function testGetHrefThrowsExceptionOnUnknownHref()
    {
        $this->setExpectedException('RuntimeException', 'Unable to construct href');

        $navigation = new Navigation();
        $page       = new Page();

        $navigation->getHref($page);
    }

    public function testIsAllowedWithNoListenersShouldReturnTrue()
    {
        $navigation = new Navigation();
        $page       = new Page();
        $this->assertTrue($navigation->isAllowed($page));
    }

    public function testIsAllowedRbac()
    {
        $rbac = new Rbac();
        $rbac->addRole('foo');
        $rbac->getRole('foo')->addPermission('bar');

        $navigation = new Navigation();
        $navigation->getEventManager()->attach(new RbacListener($rbac));

        $page = new Page();
        $page->setOptions(array('permission' => 'test'));
        $this->assertTrue($navigation->isAllowed($page));

        $page->setOptions(array('role' => 'foo', 'permission' => 'bar'));
        $this->assertTrue($navigation->isAllowed($page));

        $page->setOptions(array('role' => 'foo', 'permission' => 'baz'));
        $this->assertFalse($navigation->isAllowed($page));

        $page->setOptions(array('role' => 'foo', 'permission' => 'bar', 'assertion' => function() { return true; }));
        $this->assertTrue($navigation->isAllowed($page));

        $page->setOptions(array('role' => 'foo', 'permission' => 'bar', 'assertion' => function() { return false; }));
        $this->assertFalse($navigation->isAllowed($page));
    }
}
