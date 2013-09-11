<?php

namespace SpiffyNavigationTest\Page;

use PHPUnit_Framework_TestCase;
use SpiffyNavigation\Page\PageFactory;

class PageFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testCreatingPageFromFactory()
    {
        $input = array(
            'options' => array(
                'name' => 'Parent',
                'uri' => 'www.parent1.com',
            ),
            'pages' => array(
                array(
                    'options' => array(
                        'name' => 'child1',
                        'uri' => 'www.child1.com'
                    )
                ),
                array(
                    'options' => array(
                        'name' => 'child2',
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
            'attributes' => array(
                'foo' => 'bar'
            ),
            'options' => array(
                'name' => 'Parent',
                'uri' => 'www.parent1.com',
            )
        );

        $page = PageFactory::create($input);
        $this->assertEquals('bar', $page->getAttribute('foo'));
    }

    public function testFactorySetsOptions()
    {
        $input = array(
            'options' => array(
                'uri' => 'www.parent1.com',
                'name' => 'Parent',
            ),
            'attributes' => array(
                'class' => 'foo-bar'
            )
        );

        $page = PageFactory::create($input);
        $this->assertEquals('www.parent1.com', $page->getOption('uri'));
    }

    /**
     * @expectedException \PHPUnit_Framework_Error_Deprecated
     */
    public function testFactoryThrowsExceptionOnMissingName()
    {
        PageFactory::create(array('name' => 'deprecated', 'options' => array('uri' => 'www.test.com')));
    }
}
