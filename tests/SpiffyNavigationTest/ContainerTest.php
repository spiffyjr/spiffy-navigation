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
        $pages = $this->container1->findByname('child1');

        $this->assertCount(2, $pages);
        $this->assertEquals('child1', $pages[0]->getName());
        $this->assertEquals('child1', $pages[1]->getName());
    }

    public function testFindOneByProperty()
    {
        $page = $this->container1->findOneByProperty('uri', 'http://www.child3.com');

        $this->assertInstanceOf('SpiffyNavigation\Page\Page', $page);
        $this->assertEquals('http://www.child3.com', $page->getProperty('uri'));
    }

    public function testFndByProperty()
    {
        $pages = $this->container1->findByProperty('uri', 'http://www.child1.com');

        $this->assertCount(2, $pages);
        $this->assertEquals('http://www.child1.com', $pages[0]->getProperty('uri'));
        $this->assertEquals('http://www.child1.com', $pages[1]->getProperty('uri'));
    }
}