<?php

namespace SpiffyNavigationTest\Page;

use PHPUnit_Framework_TestCase;
use SpiffyNavigation\Page\PageFactory;

class PageFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testCreatingPageFromFactory()
    {
        $input = array(
            'name' => 'Parent',
            'properties' => array(
                'uri' => 'www.parent1.com',
            ),
            'pages' => array(
                array(
                    'name' => 'child1',
                    'properties' => array(
                        'uri' => 'www.child1.com'
                    )
                ),
                array(
                    'name' => 'child2',
                    'properties' => array(
                        'uri' => 'www.child2.com',
                    ),
                )
            )
        );

        $page = PageFactory::create($input);
        $this->assertEquals(true, $page->hasChildren());
        $this->assertEquals(2, iterator_count($page));
    }

    public function testFactorySetsAttributes()
    {
        $input = array(
            'name' => 'Parent',
            'properties' => array(
                'uri' => 'www.parent1.com',
                'foo' => 'bar'
            )
        );

        $page = PageFactory::create($input);
        $this->assertEquals('bar', $page->getProperty('foo'));
    }

    public function testFactorySetsProperties()
    {
        $input = array(
            'name' => 'Parent',
            'properties' => array(
                'uri' => 'www.parent1.com',
            ),
            'attributes' => array(
                'class' => 'foo-bar'
            )
        );

        $page = PageFactory::create($input);
        $this->assertEquals('foo-bar', $page->getAttribute('class'));
    }

    public function testFactoryThrowsExceptionOnMissingName()
    {
        $this->setExpectedException('InvalidArgumentException');
        PageFactory::create(array('properties' => array('uri' => 'www.test.com')));
    }

    public function testFactoryThrowsExceptionOnUnknownType()
    {
        $this->setExpectedException('InvalidArgumentException');
        PageFactory::create(array());
    }
}
