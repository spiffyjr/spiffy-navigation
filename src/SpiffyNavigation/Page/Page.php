<?php

namespace SpiffyNavigation\Page;

use InvalidArgumentException;
use SpiffyNavigation\Container;

class Page extends Container
{
    /**
     * Page name (does not have to be unique).
     * @var string|null
     */
    protected $name;

    /**
     * Attributes used to construct the page element.
     * @var array
     */
    protected $attributes = array();

    /**
     * Custom properties for the page.
     * @var array
     */
    protected $properties = array();

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
     * @return Page
     * @throws InvalidArgumentException
     */
    public function addChild($pageOrSpec)
    {
        if (is_array($pageOrSpec)) {
            $pageOrSpec = PageFactory::create($pageOrSpec);
        } else if (!$pageOrSpec instanceof Page) {
            throw new InvalidArgumentException('Valid page types are an array or an Page instance');
        }

        $pageOrSpec->setParent($this);
        $this->children[] = $pageOrSpec;

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
     * Set a property.
     *
     * @param string $property
     * @param mixed $value
     * @return Page
     */
    public function setProperty($property, $value)
    {
        $this->properties[$property] = $value;
        return $this;
    }

    /**
     * Get a property.
     *
     * @param string $property
     * @return mixed
     */
    public function getProperty($property)
    {
        return isset($this->properties[$property]) ? $this->properties[$property] : null;
    }

    /**
     * @param array $properties
     * @return Page
     */
    public function setProperties(array $properties)
    {
        $this->properties = $properties;
        return $this;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Set a attribute.
     *
     * @param string $attribute
     * @param mixed $value
     * @return Page
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
     * @return Page
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
     * @param null|string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }
}