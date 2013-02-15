<?php

namespace SpiffyNavigation\View\Helper;

use RecursiveIteratorIterator;
use RuntimeException;
use SpiffyNavigation\Page\Page;

class NavigationMenu extends AbstractHelper
{
    /**
     * An array of valid HTML5 attributes.
     * @var array
     */
    protected $validAttribs = array(
        'class',
        'href',
        'id',
        'rel',
        'target'
    );

    /**
     * Render a default menu.
     *
     * @param string|\SpiffyNavigation\Container|null $container
     * @param array $options
     * @return string
     */
    public function renderMenu($container = null, array $options = array())
    {
        $html      = '';
        $container = $this->getContainer($container);
        $options   = new Options\NavigationMenu($options);
        $iterator  = new RecursiveIteratorIterator($container, RecursiveIteratorIterator::SELF_FIRST);
        $iterator->setMaxDepth($options->getMaxDepth());

        /** @var \SpiffyNavigation\Page\Page $page */
        $prevDepth = -1;
        foreach($iterator as $page) {
            $depth = $iterator->getDepth();
            if ($depth == $options->getMinDepth()) {
                $prevDepth = $depth;
                continue;
            }
            if ($depth > $prevDepth) {
                $html .= sprintf('<ul%s>', $prevDepth == $options->getMinDepth() ? ' class="' . $options->getUlClass() .'"' : '');
            } else if ($prevDepth > $depth) {
                for ($i = $prevDepth; $i > $depth; $i--) {
                    $html .= '</li>';
                    $html .= '</ul>';
                }
                $html .= '</li>';
            } else {
                $html .= '</li>';
            }

            $liClass = $this->navigation->isActive($page) ? ' class="' . $options->getActiveClass() . '"' : '';
            $html .= sprintf('<li%s>%s', $liClass, $this->htmlify($page));

            $prevDepth = $depth;
        }

        if ($html) {
            for ($i = $prevDepth+1; $i > 0; $i--) {
                $html .= '</li></ul>';
            }
        }

        return $html;
    }

    /**
     * Default render.
     *
     * @param string|\SpiffyNavigation\Container|null $container
     * @return string
     */
    public function render($container = null)
    {
        $container = $this->getContainer($container);
        if ($this->getPartial()) {
            return $this->renderPartial($container);
        }
        return $this->renderMenu($container);
    }

    /**
     * Convert a page to the html version.
     *
     * @param Page $page
     * @param bool $escapeLabel
     * @return string
     */
    protected function htmlify(Page $page, $escapeLabel = true)
    {
        if ($page->getProperty('label')) {
            $label = $page->getProperty('label');
        } else {
            $label = $page->getName();
        }

        $href = null;
        try {
            $href = $this->navigation->getHref($page);
        } catch (RuntimeException $e) {
            ; // intentionally left blank
        }

        $attribs = $page->getAttributes();
        if ($href) {
            $element         = 'a';
            $attribs['href'] = $href;
        } else {
            $element = 'span';
        }

        return sprintf('<%s%s>%s</%s>', $element, $this->htmlAttribs($attribs), $label, $element);
    }
}