<?php

namespace SpiffyNavigationTest;

use PHPUnit_Framework_TestCase;
use SpiffyNavigation\Container;
use SpiffyNavigation\Page\Page;

class ContainerTest extends PHPUnit_Framework_TestCase
{
    public function testFindPageByAttribute()
    {
        $container = new Container();

        $child1 = new Page();
        $child1->setAttributes(array('label' => 'child1'));

        $child2 = new Page();
        $child2->setAttributes(array('label' => 'child2'));

        $child3 = new page();
        $child3->setAttributes(array('label' => 'child2'));

        $container->addPage($child1)
                  ->addPage($child2)
                  ->addPage($child3);

        $child = $container->findOneBy('label', 'child1', false);
        $this->assertEquals($child1, $child);

        $children = $container->findBy('label', 'child2', false);
        $this->assertCount(2, $children);
        $this->assertEquals($child2, $children[0]);
        $this->assertEquals($child3, $children[1]);
    }
}