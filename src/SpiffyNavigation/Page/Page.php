<?php

namespace SpiffyNavigation\Page;

use InvalidArgumentException;
use SpiffyNavigation\Container;

class Page extends Container
{
    /**
     * Attributes used to construct the page element.
     * @var array
     */
    protected $attributes = array();

    /**
     * Custom options for the page.
     * @var array
     */
    protected $options = array();

    /**
     * Parent of this node, if any.
     * @var null|Page
     */
    protected $parent;

    /**
     * Adds a child to the current page. The factory method is used if an array
     * is provided.
     *
     * @param array|Page $pageOrSpec
     * @return $this
     * @throws InvalidArgumentException
     */
    public function addChild($pageOrSpec)
    {
        if (is_array($pageOrSpec)) {
            $pageOrSpec = PageFactory::create($pageOrSpec);
        } elseif (!$pageOrSpec instanceof Page) {
            throw new InvalidArgumentException('Valid page types are an array or an Page instance');
        }

        $pageOrSpec->setParent($this);
	    $this->insertChild($pageOrSpec);

        return $this;
    }

    /**
     * Check if node has a parent.
     * @return bool
     */
    public function hasParent()
    {
        return null !== $this->parent;
    }

    /**
     * @param $parent
     * @return Page
     */
    public function setParent(Page $parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return null|Page
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param string $option
     * @param mixed $value
     * @return $this
     */
    public function setOption($option, $value)
    {
        $this->options[$option] = $value;
        return $this;
    }

    /**
     * @param string $option
     * @return mixed
     */
    public function getOption($option)
    {
        return isset($this->options[$option]) ? $this->options[$option] : null;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set a attribute.
     *
     * @param string $attribute
     * @param mixed $value
     * @return $this
     */
    public function setAttribute($attribute, $value)
    {
        $this->attributes[$attribute] = $value;
        return $this;
    }

    /**
     * Get a attribute.
     *
     * @param string $attribute
     * @return mixed
     */
    public function getAttribute($attribute)
    {
        return isset($this->attributes[$attribute]) ? $this->attributes[$attribute] : null;
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @deprecated
     * @param null|string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->setOption('name', $name);
        return $this;
    }

    /**
     * @deprecated
     * @return null|string
     */
    public function getName()
    {
        return $this->getOption('name');
    }
}
