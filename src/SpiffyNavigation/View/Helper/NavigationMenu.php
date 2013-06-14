<?php

namespace SpiffyNavigation\View\Helper;

use RecursiveIteratorIterator;
use RuntimeException;
use SpiffyNavigation\Filter\IsAllowedFilter;
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
        $filter    = new IsAllowedFilter($this->getContainer($container), $this->navigation);
        $options   = new NavigationMenuOptions($options);
        $iterator  = new RecursiveIteratorIterator($filter, RecursiveIteratorIterator::SELF_FIRST);
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
            $html   .= sprintf('<li%s>%s', $liClass, $this->htmlify($page));

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
     * Renders the given $container by invoking the partial view helper
     *
     * The container will simply be passed on as a model to the view script
     * as-is, and will be available in the partial script as 'container', e.g.
     * <code>echo 'Number of pages: ', count($this->container);</code>.
     *
     * @param string|\SpiffyNavigation\Container|null $container [optional] container to pass to view script.
     * @param string $partial [optional] partial view script to use.
     * @return string
     * @throws RuntimeException if no partial provided
     */
    public function renderPartial($container = null, $partial = null)
    {
        $container = $this->getContainer($container);

        if (null === $partial) {
            $partial = $this->getPartial();
        }

        if (empty($partial)) {
            throw new RuntimeException(
                'Unable to render menu: No partial view script provided'
            );
        }

        $model = array(
            'container'  => $container,
            'navigation' => $this->navigation
        );

        return $this->view->render($partial, $model);
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