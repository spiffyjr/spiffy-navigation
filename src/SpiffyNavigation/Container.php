<?php

namespace SpiffyNavigation;

use InvalidArgumentException;
use RecursiveIterator;
use RecursiveIteratorIterator;
use SpiffyNavigation\Page\Page;
use SpiffyNavigation\Page\PageFactory;

class Container implements RecursiveIterator
{
    const FIND_TYPE_NAME      = 0;
    const FIND_TYPE_PROPERTY  = 1;

    /**
     * Index of current active child.
     * @var int
     */
    protected $index = 0;

    /**
     * Array of child nodes.
     * @var array
     */
    protected $children = array();

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return $this->children[$this->index];
    }

    /**
     * {@inheritDoc}
     */
    public function next()
    {
        $this->index++;
    }

    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * {@inheritDoc}
     */
    public function valid()
    {
        return isset($this->children[$this->index]);
    }

    /**
     * {@inheritDoc}
     */
    public function rewind()
    {
        $this->index = 0;
    }

    /**
     * {@inheritDoc}
     */
    public function hasChildren()
    {
        return count($this->children) > 0;
    }

    /**
     * {@inheritDoc}
     */
    public function getChildren()
    {
        return $this->children[$this->index];
    }

    /**
     * Adds a page to the container.
     *
     * @param Page $page
     * @return Container
     * @throws InvalidArgumentException
     */
    public function addPage(Page $page)
    {
        $this->children[] = $page;
        return $this;
    }

    /**
     * Finds a single child by name.
     *
     * @param string $value
     * @return Page|Null
     */
    public function findOneByName($value)
    {
        $iterator = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);

        /** @var \SpiffyNavigation\Page\Page $page */
        foreach ($iterator as $page) {
            if ($page->getName() == $value) {
                return $page;
            }
        }

        return null;
    }

    /**
     * Finds all children by name.
     *
     * @param string $value
     * @return array
     */
    public function findByName($value)
    {
        $result   = array();
        $iterator = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);

        /** @var \SpiffyNavigation\Page\Page $page */
        foreach ($iterator as $page) {
            if ($page->getName() == $value) {
                $result[] = $page;
            }
        }

        return $result;
    }

    /**
     * Finds a single child by property.
     *
     * @param string $property
     * @param mixed $value
     * @return Page|Null
     */
    public function findOneByProperty($property, $value)
    {
        $iterator = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);

        /** @var \SpiffyNavigation\Page\Page $page */
        foreach ($iterator as $page) {
            if ($page->getProperty($property) == $value) {
                return $page;
            }
        }

        return null;
    }

    /**
     * Finds all children by property.
     *
     * @param string $property
     * @param mixed $value
     * @return array
     */
    public function findByProperty($property, $value)
    {
        $result   = array();
        $iterator = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);

        /** @var \SpiffyNavigation\Page\Page $page */
        foreach ($iterator as $page) {
            if ($page->getProperty($property) == $value) {
                $result[] = $page;
            }
        }

        return $result;
    }
}