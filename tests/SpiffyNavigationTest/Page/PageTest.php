<?php

namespace SpiffyNavigationTest\Page;

use SpiffyNavigation\Page\Page;
use SpiffyNavigationTest\AbstractTest;

class PageTest extends AbstractTest
{
    public function testSettingDataAttributeIsValid()
    {
        $attributes = array('data-id' => 1);

        $page = new Page();
        $page->setAttributes($attributes);

        $this->assertEquals($attributes, $page->getAttributes());
    }

    public function testCreatingPageFromFactory()
    {
        $input = array(
            'attributes' => array(
                'href' => 'www.parent1.com',
            ),
            'pages' => array(
                array(
                    'attributes' => array(
                        'href' => 'www.child1.com'
                    )
                ),
                array(
                    'attributes' => array(
                        'href' => 'www.child2.com',
                    ),
                    'pages' => array(
                        array(
                            'attributes' => array(
                                'href' => 'www.subchild1.com'
                            )
                        )
                    )
                )
            )
        );

        $page = Page::factory($input);

        $this->assertEquals(true, $page->hasChildren());
    }

    public function testInvalidArgumentExceptionIsThrownOnInvalidChildType()
    {
        $this->setExpectedException('InvalidArgumentException', 'Valid page types are an array or Page instance');

        $page1 = new Page();
        $page1->addChild(true);
    }

    public function testSettingChildFromArray()
    {
        $page1 = new Page();
        $page1->addChild(array('attributes' => array('href' => 'www.child1.com')));

        $this->assertEquals(true, $page1->hasChildren());
        $this->assertEquals(1, iterator_count($page1));
    }

    public function testSettingChildFromPage()
    {
        $page1  = new Page();
        $child1 = new Page();
        $child2 = new Page();

        $page1->addChild($child1);
        $page1->addChild($child2);

        $this->assertEquals(true, $page1->hasChildren());
        $this->assertEquals(2, iterator_count($page1));
    }
}