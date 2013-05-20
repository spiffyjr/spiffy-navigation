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
            $isActive = $this->navigation->isActive($page);
            $depth = $iterator->getDepth();
            if ($depth == $options->getMinDepth()) {
                $prevDepth = $depth;
                continue;
            }
            if ($options->getMinDepth() > -1 && !$this->navigation->isActive($page->getParent())) {
                $prevDepth = $depth;
                continue;
            }
            if ($depth > $prevDepth) {
                $ulClass = array();
                $ulRole = '';
                if ($prevDepth == $options->getMinDepth()) {
                    $ulClass[] = $options->getUlClass();
                }
                if ($depth > 0 && $options->getDropdown() === false) {
                    $ulClass[] = 'dropdown-menu';
                    $ulRole = 'menu';
                }
                $html .= sprintf(
                    '<ul%s%s>',
                    count($ulClass) ? ' class="' . implode(' ', $ulClass).'"' : '',
                    $ulRole ? 'role="'.$ulRole.'"' : ''
                );
            } else if ($prevDepth > $depth) {
                for ($i = $prevDepth; $i > $depth; $i--) {
                    $html .= '</li>';
                    $html .= '</ul>';
                }
                $html .= '</li>';
            } else {
                $html .= '</li>';
            }

            $liClass = array();
            $dataToggle = '';
            if ($isActive) {
                $liClass[] = $options->getActiveClass();
            }
            if ($page->hasChildren() && $options->getDropdown() === false) {
                $liClass[] = 'dropdown';
//                $dataToggle = ' data-toggle="dropdown"';
            }

            $html .= sprintf(
                '<li%s%s>%s',
                count($liClass) ? ' class="' . implode(' ', $liClass).'"' : '',
                $dataToggle,
                $this->htmlify($page, $options)
            );

            $prevDepth = $depth;
        }

        if ($html) {
            if ($options->getMinDepth() == -1) {
                $prevDepth++;
            }
            for ($i = $prevDepth; $i > 0; $i--) {
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
    protected function htmlify(Page $page, Options\NavigationMenu $options, $escapeLabel = true)
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
            if ($page->hasChildren() && $options->getDropdown() === false) {
                $label .= '<b class="caret"></b>';
                $attribs['class'] = 'dropdown-toggle';
                $attribs['data-toggle'] = 'dropdown';
            }
        } else {
            $element = 'span';
        }

        return sprintf('<%s%s>%s</%s>', $element, $this->htmlAttribs($attribs), $label, $element);
    }
}