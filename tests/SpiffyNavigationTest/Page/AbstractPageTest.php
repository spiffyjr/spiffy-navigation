<?php

namespace SpiffyNavigationTest\Page;

use SpiffyNavigation\Page\Page;
use SpiffyNavigationTest\AbstractTest;

class AbstractPageTest extends AbstractTest
{
    public function testInvalidArgumentExceptionIsThrownOnInvalidChildType()
    {
        $this->setExpectedException('InvalidArgumentException');

        $page1 = new Page();
        $page1->addChild(true);
    }

    public function testSettingChildFromArray()
    {
        $page1 = new Page();
        $page1->addChild(array('name' => 'child1', 'properties' => array('uri' => 'www.child1.com')));

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