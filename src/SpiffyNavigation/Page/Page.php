<?php

namespace SpiffyNavigation\Page;

use InvalidArgumentException;
use RuntimeException;
use SpiffyNavigation\Container;

class Page extends Container implements PageInterface
{
    /**
     * Additional attributes for the page.
     * @var array
     */
    protected $attributes = array();

    /**
     * Parent of this node, if any.
     * @var null|Page
     */
    protected $parent;

    /**
     * Creates a page tree from an array.
     *
     * @param array $spec
     * @return Page
     */
    public static function factory(array $spec)
    {
        $page = new Page();

        if (isset($spec['attributes'])) {
            $page->setAttributes($spec['attributes']);
        }

        if (isset($spec['pages'])) {
            foreach($spec['pages'] as $childSpec) {
                $page->addChild(self::factory($childSpec));
            }
        }

        return $page;
    }

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
            $pageOrSpec = self::factory($pageOrSpec);
        } else if (!$pageOrSpec instanceof Page) {
            throw new InvalidArgumentException('Valid page types are an array or Page instance');
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
}