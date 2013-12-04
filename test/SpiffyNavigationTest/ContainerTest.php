<?php

namespace SpiffyNavigationTest;

class ContainerTest extends AbstractTest
{
    public function testFindOneByName()
    {
        $page = $this->container1->findOneByName('child1');

        $this->assertInstanceOf('SpiffyNavigation\Page\Page', $page);
        $this->assertEquals('child1', $page->getName());
    }

    public function testFindByName()
    {
        $pages = $this->container1->findByName('child1');

        $this->assertCount(2, $pages);
        $this->assertInstanceOf('SpiffyNavigation\Page\Page', $pages[0]);
        $this->assertInstanceOf('SpiffyNavigation\Page\Page', $pages[1]);
        $this->assertEquals('child1', $pages[0]->getName());
        $this->assertEquals('child1', $pages[1]->getName());
    }

    public function testFindOneByOption()
    {
        $page = $this->container1->findOneByOption('name', 'child1');

        $this->assertInstanceOf('SpiffyNavigation\Page\Page', $page);
        $this->assertEquals('child1', $page->getName());
    }

    public function testFindByOption()
    {
        $pages = $this->container1->findByOption('name', 'child1');

        $this->assertCount(2, $pages);
        $this->assertInstanceOf('SpiffyNavigation\Page\Page', $pages[0]);
        $this->assertInstanceOf('SpiffyNavigation\Page\Page', $pages[1]);
        $this->assertEquals('child1', $pages[0]->getName());
        $this->assertEquals('child1', $pages[1]->getName());
    }

    public function testFindOneByAttribute()
    {
        $page = $this->container1->findOneByAttribute('class', 'child3-class');

        $this->assertInstanceOf('SpiffyNavigation\Page\Page', $page);
        $this->assertEquals('child3-class', $page->getAttribute('class'));
    }

    public function testFndByAttribute()
    {
        $pages = $this->container1->findByAttribute('class', 'child1-class');

        $this->assertCount(1, $pages);
        $this->assertInstanceOf('SpiffyNavigation\Page\Page', $pages[0]);
        $this->assertEquals('child1-class', $pages[0]->getAttribute('class'));
    }
}