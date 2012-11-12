<?php

namespace SpiffyNavigationTest\Service;

use PHPUnit_Framework_TestCase;
use ReflectionClass;
use SpiffyNavigation\Container;
use SpiffyNavigation\Page\Page;
use SpiffyNavigation\Service\Navigation;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\Mvc\Router\Http\Regex as RegexRoute;

class NavigationTest extends PHPUnit_Framework_TestCase
{
    public function testIsActiveCache()
    {
        $routeMatch = new RouteMatch(array());
        $routeMatch->setMatchedRouteName('test');

        $router = new TreeRouteStack();
        $router->addRoute('test', new Literal('/foo-bar'));

        $page = new Page();
        $page->setAttributes(array('route' => 'test'));

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

    public function testIsActiveWithRecursion()
    {
        $routeMatch = new RouteMatch(array());
        $routeMatch->setMatchedRouteName('test');

        $router = new TreeRouteStack();
        $router->addRoute('test', new Literal('/foo-bar'));

        $child = new Page();
        $child->setAttributes(array('route' => 'test'));

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
        $child->setAttributes(array('route' => 'test'));

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
        $page->setAttributes(array('route' => 'test'));

        $navigation = new Navigation();
        $navigation->setRouteMatch($routeMatch);

        $this->assertTrue($navigation->isActive($page));
    }

    public function testGetHrefWithFragment()
    {
        $navigation = new Navigation();

        $page = new Page();
        $page->setAttributes(array('href' => 'www.foo.com', 'fragment' => '#bar'));
        $this->assertEquals('www.foo.com#bar', $navigation->getHref($page));

        $page = new Page();
        $page->setAttributes(array('href' => 'www.foo.com', 'fragment' => 'bar'));
        $this->assertEquals('www.foo.com#bar', $navigation->getHref($page));
    }

    public function testHrefCache()
    {
        $navigation = new Navigation();

        $page = new Page();
        $page->setAttributes(array('uri' => 'www.foobar.com'));

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

    public function testGetHrefThrowsExceptionWithRouteAndNoRouteMatch()
    {
        $this->setExpectedException('RuntimeException', 'Cannot construct href from route with no router');

        $navigation = new Navigation();

        $page = new Page();
        $page->setAttributes(array('route' => 'test'));

        $navigation->getHref($page);
    }

    public function testGetHrefFromRouteWithParams()
    {
        $route = new RegexRoute('/foo/edit/(?<id>\d+)', '/foo/edit/%id%');

        $router = new TreeRouteStack();
        $router->addRoute('test', $route);

        $navigation = new Navigation();
        $navigation->setRouter($router);

        $page = new Page();
        $page->setAttributes(array('route' => 'test', 'params' => array('id' => 1234)));

        $this->assertEquals('/foo/edit/1234', $navigation->getHref($page));
    }

    public function testGetHrefFromRoute()
    {
        $route = new Literal('/foo-bar');

        $router = new TreeRouteStack();
        $router->addRoute('test', $route);

        $navigation = new Navigation();
        $navigation->setRouter($router);

        $page = new Page();
        $page->setAttributes(array('route' => 'test'));

        $this->assertEquals('/foo-bar', $navigation->getHref($page));
    }

    public function testGetHrefIfUriOrHrefAttributeIsSet()
    {
        $navigation = new Navigation();

        $page = new Page();
        $page->setAttributes(array('href' => 'www.google.com'));

        $page2 = new Page();
        $page2->setAttributes(array('href' => 'framework.zend.com'));

        $this->assertEquals('www.google.com', $navigation->getHref($page));
        $this->assertEquals('framework.zend.com', $navigation->getHref($page2));
    }

    public function testGetHrefThrowsExceptionOnUnknownHref()
    {
        $this->setExpectedException('RuntimeException', 'Unable to construct href');

        $navigation = new Navigation();
        $page = new Page();

        $navigation->getHref($page);
    }

    public function testGetContainerWithInvalidNameThrowsException()
    {
        $this->setExpectedException('InvalidArgumentException');

        $navigation = new Navigation();
        $navigation->getContainer('foo');
    }

    public function testHasContainer()
    {
        $navigation = new Navigation();
        $container  = new Container();

        $navigation->addContainer('test', $container);

        $this->assertTrue($navigation->hasContainer('test'));
        $this->assertFalse($navigation->hasContainer('foo'));
    }

    public function testAddContainerThrowsExceptionOnDuplicateName()
    {
        $this->setExpectedException('InvalidArgumentException');

        $navigation = new Navigation();
        $container  = new Container();

        $navigation->addContainer('test', $container);
        $navigation->addContainer('test', $container);
    }

    public function testClearContainerResetsContainers()
    {
        $navigation = new Navigation();
        $container  = new Container();

        $navigation->addContainer('test', $container);
        $navigation->clearContainers();

        $this->assertCount(0, $navigation->getContainers());
    }

    public function testSetContainerSetsANewContainer()
    {
        $navigation = new Navigation();
        $container  = new Container();

        $navigation->addContainer('test', $container);
        $this->assertCount(1, $navigation->getContainers());
    }

    public function testGetContainerReturnsAllContainers()
    {
        $navigation = new Navigation();
        $container  = new Container();
        $container2 = new Container();

        $navigation->addContainer('test', $container);
        $navigation->addContainer('test2', $container2);

        $containers = $navigation->getContainers();
        $this->assertEquals($container, $containers['test']);
        $this->assertEquals($container2, $containers['test2']);
    }

    public function testRemoveContainerWithInvalidNameThrowsException()
    {
        $this->setExpectedException('InvalidArgumentException');

        $navigation = new Navigation();
        $navigation->removeContainer('foo');
    }

    public function testRemoveContainer()
    {
        $navigation = new Navigation();
        $container  = new Container();
        $container2 = new Container();

        $navigation->addContainer('test', $container);
        $navigation->addContainer('test2', $container2);

        $navigation->removeContainer('test');

        $containers = $navigation->getContainers();
        $this->assertEquals($container2, $containers['test2']);
    }
}