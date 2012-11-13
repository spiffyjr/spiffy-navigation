<?php

namespace SpiffyNavigationTest;

use SpiffyNavigation\Container;
use SpiffyNavigation\Page\Page;

class ContainerTest extends AbstractTest
{
    public function testFindPageByAttribute()
    {
        $child = $this->nav->getContainer('container1')
                           ->findOneBy('label', 'child1', false);
        $attrs = $child->getAttributes();
        $this->assertEquals('child1', $attrs['label']);

        $children = $this->nav->getContainer('container1')
                              ->findBy('foo', 'bar', false);
        $this->assertCount(2, $children);

        $attrs = $children[0]->getAttributes();
        $this->assertEquals('bar', $attrs['foo']);

        $attrs = $children[1]->getAttributes();
        $this->assertEquals('bar', $attrs['foo']);
    }
}