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
     * Return the current element
     *
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return $this->children[$this->index];
    }

    /**
     * Move forward to next element
     *
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        $this->index++;
    }

    /**
     * Return the key of the current element
     *
     * @link http://php.net/manual/en/iterator.key.php
     * @return int int on success, or null on failure.
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * Checks if current position is valid
     *
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return isset($this->children[$this->index]);
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->index = 0;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Returns if an iterator can be created fot the current entry.
     *
     * @link http://php.net/manual/en/recursiveiterator.haschildren.php
     * @return bool true if the current entry can be iterated over, otherwise returns false.
     */
    public function hasChildren()
    {
        return count($this->children) > 0;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Returns an iterator for the current entry.
     *
     * @link http://php.net/manual/en/recursiveiterator.getRoles.php
     * @return RecursiveIterator An iterator for the current entry.
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
     * @param string $property
     * @param mixed $value
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