<?php
/**
 * LazyContainerTest
 *
 * @category  SpiffyNavigationTest
 * @package   SpiffyNavigationTest
 * @copyright 2014 ACSI Holding bv (http://www.acsi.eu)
 * @version   SVN: $Id$
 */


namespace SpiffyNavigationTest;


use SpiffyNavigation\LazyContainer;
use SpiffyNavigation\Page\Page;
use SpiffyNavigation\Service\Navigation;

class LazyContainerTest extends \PHPUnit_Framework_TestCase
{
    /** @var LazyContainer */
    protected $container;

    public function setUp()
    {

        $provider = \Mockery::mock('SpiffyNavigation\Provider\LazyProviderInterface');
        $container = new LazyContainer($provider);

        $provider->shouldReceive('getPages')->andReturnUsing(
            function() use ($container) {
                $container->addPage(new Page());
                $container->addPage(new Page());
            }
        );

        $container->setProvider($provider);

        $this->container = $container;
    }

    public function testIfContainerIsEmptyOnInstantiate()
    {
        $this->assertFalse($this->container->isInitialized());
    }

    public function testIfCurrentInitializesContainer()
    {
        $pageCount = 0;

        foreach($this->container as $page) {
            $pageCount++;
        }

        $this->assertEquals(2, $pageCount);
    }
} 