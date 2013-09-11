<?php

namespace SpiffyNavigation\Page;

class PageFactory
{
    protected function __construct()
    {
    }

    /**
     * Creates a page tree from an array.
     *
     * @param array $spec
     * @return Page
     */
    public static function create(array $spec)
    {
        $page = new Page();

        if (isset($spec['name'])) {
            trigger_error('usage of "name" is deprecated - set the name as an attribute instead', E_USER_DEPRECATED);
            $page->setOption('name', $spec['name']);
        }

        if (isset($spec['pages'])) {
            foreach($spec['pages'] as $childSpec) {
                $page->addChild(self::create($childSpec));
            }
        }

        if (isset($spec['attributes'])) {
            $page->setAttributes($spec['attributes']);
        }

        if (isset($spec['options'])) {
            $page->setOptions($spec['options']);
        }

        return $page;
    }
}