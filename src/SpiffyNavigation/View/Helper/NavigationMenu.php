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
     * @param string|\SpiffyNavigation\AbstractContainer|null $container
     * @param array $options
     * @return string
     */
    public function renderMenu($container = null, array $options = array())
    {
        $html      = '';
        $container = $this->getContainer($container);
        $options   = new Options\NavigationMenu($options);
        $iterator  = new RecursiveIteratorIterator($container, RecursiveIteratorIterator::SELF_FIRST);

        /** @var \SpiffyNavigation\Page\PageInterface $page */
        $prevDepth = -1;
        foreach($iterator as $page) {
            $depth = $iterator->getDepth();

            if ($depth > $prevDepth) {
                $html .= sprintf('<ul%s>', $depth == 0 ? ' class="' . $options->getUlClass() .'"' : '');
            } else if ($prevDepth > $depth) {
                for ($i = $prevDepth; $i > $depth; $i--) {
                    $html .= '</li>';
                    $html .= '</ul>';
                }
                $html .= '</li>';
            } else {
                $html .= '</li>';
            }

            $liClass = $this->navigation->isActive($page) ? ' class="active"' : '';
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
     * @param string|\SpiffyNavigation\AbstractContainer|null $container
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
        $attrs = $page->getAttributes();
        $label = isset($attrs['label']) ? $attrs['label'] : '';
        $href  = null;

        try {
            $href = $this->navigation->getHref($page);
        } catch (RuntimeException $e) {
            ; // intentionally left blank
        }

        if ($href) {
            $element       = 'a';
            $attrs['href'] = $href;
        } else {
            $element = 'span';
        }

        $attrs = $this->cleanAttribs($attrs, $this->validAttribs);
        return sprintf('<%s%s>%s</%s>', $element, $this->htmlAttribs($attrs), $label, $element);
    }
}