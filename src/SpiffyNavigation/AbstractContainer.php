<?php

namespace SpiffyNavigation;

use InvalidArgumentException;
use RecursiveIterator;
use RecursiveIteratorIterator;
use SpiffyNavigation\Page\PageInterface;

abstract class AbstractContainer implements RecursiveIterator
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
     * @param PageInterface $page
     * @return AbstractContainer
     * @throws InvalidArgumentException
     */
    public function addPage(PageInterface $page)
    {
        $this->children[] = $page;
        return $this;
    }

    /**
     * Finds a single child by attribute.
     *
     * @param string $attribute
     * @param mixed $value
     * @return PageInterface|Null
     */
    public function findOneBy($attribute, $value)
    {
        $result = $this->find($attribute, $value);
        if (empty($result)) {
            return null;
        }
        return $result;
    }

    /**
     * Finds all children by attribute. If $includeAttributes is true then
     * both attributes and attributes are searched.
     *
     * @param string $attribute
     * @param mixed $value
     * @return array
     */
    public function findBy($attribute, $value)
    {
        return $this->find($attribute, $value, true);
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @param bool $all
     * @return null|Page\Page
     */
    protected function find($attribute, $value, $all = false)
    {
        $result   = array();
        $iterator = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);

        /** @var \SpiffyNavigation\Page\PageInterface $page */
        foreach ($iterator as $page) {
            $attributes = $page->getAttributes();

            if (isset($attributes[$attribute]) && $attributes[$attribute] === $value) {
                if ($all) {
                    $result[] = $page;
                } else {
                    return $page;
                }
            }
        }

        return $result;
    }
}