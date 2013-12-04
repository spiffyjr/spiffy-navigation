<?php

namespace SpiffyNavigation\Listener;

use RuntimeException;
use SpiffyNavigation\NavigationEvent;
use SpiffyNavigation\Service\Navigation;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;

class HrefListener extends AbstractListenerAggregate
{
    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(Navigation::EVENT_GET_HREF, array($this, 'getHref'));
    }

    /**
     * @param NavigationEvent $e
     * @throws \RuntimeException
     */
    public function getHref(NavigationEvent $e)
    {
        if ($e->getResult()) {
            return;
        }

        $href = null;
        $page = $e->getTarget();
        $nav  = $e->getNavigation();

        /** @var $page \SpiffyNavigation\Page\Page */
        if ($page->getOption('uri')) {
            $href = $page->getOption('uri');
        } elseif ($page->getOption('route')) {
            if (!$nav->getRouter()) {
                throw new RuntimeException('Cannot construct mvc href with no router');
            }

            $href = $nav->getRouter()->assemble((array)
                $page->getOption('params'),
                array('name' => $page->getOption('route'))
            );
        } elseif ($page->getOption('href')) {
            $href = $page->getAttribute('href');
        }

        if ($href) {
            if ($page->getOption('fragment')) {
                $href .= '#' . trim($page->getOption('fragment'), '#');
            }

            $e->setResult($href);
        }
    }
}