<?php

namespace SpiffyNavigation;

use InvalidArgumentException;
use RecursiveIterator;
use RecursiveIteratorIterator;
use SpiffyNavigation\Page\Page;
use SpiffyNavigation\Page\PageFactory;

class Container implements RecursiveIterator
{
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
     * @deprecated
     * @param string $name
     * @return Page|null
     */
    public function findOneByName($name)
    {
        return $this->findOneByOption('name', $name);
    }

    /**
     * @deprecated
     * @param string $name
     * @return array
     */
    public function findByName($name)
    {
        return $this->findByOption('name', $name);
    }

    /**
     * Finds a single child by attribute.
     *
     * @param string $attribute
     * @param mixed $value
     * @return Page|null
     */
    public function findOneByAttribute($attribute, $value)
    {
        $iterator = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);

        /** @var \SpiffyNavigation\Page\Page $page */
        foreach ($iterator as $page) {
            if ($page->getAttribute($attribute) == $value) {
                return $page;
            }
        }

        return null;
    }

    /**
     * Finds all children by name.
     *
     * @param string $attribute
     * @param mixed $value
     * @return array
     */
    public function findByAttribute($attribute, $value)
    {
        $result   = array();
        $iterator = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);

        /** @var \SpiffyNavigation\Page\Page $page */
        foreach ($iterator as $page) {
            if ($page->getAttribute($attribute) == $value) {
                $result[] = $page;
            }
        }

        return $result;
    }

    /**
     * Finds a single child by option.
     *
     * @param string $option
     * @param mixed $value
     * @return Page|null
     */
    public function findOneByOption($option, $value)
    {
        $iterator = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);

        /** @var \SpiffyNavigation\Page\Page $page */
        foreach ($iterator as $page) {
            if ($page->getOption($option) == $value) {
                return $page;
            }
        }

        return null;
    }

    /**
     * Finds all children by option.
     *
     * @param string $option
     * @param mixed $value
     * @return array
     */
    public function findByOption($option, $value)
    {
        $result   = array();
        $iterator = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);

        /** @var \SpiffyNavigation\Page\Page $page */
        foreach ($iterator as $page) {
            if ($page->getOption($option) == $value) {
                $result[] = $page;
            }
        }

        return $result;
    }
}