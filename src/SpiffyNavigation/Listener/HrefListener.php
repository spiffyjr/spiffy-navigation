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
        if ($page->getProperty('uri')) {
            $href = $page->getProperty('uri');
        } else if ($page->getProperty('route')) {
            if (!$nav->getRouter()) {
                throw new RuntimeException('Cannot construct mvc href with no router');
            }

            $href = $nav->getRouter()->assemble((array)
                $page->getProperty('params'),
                array('name' => $page->getProperty('route'))
            );
        } else if ($page->getProperty('href')) {
            $href = $page->getAttribute('href');
        }

        if ($href) {
            if ($page->getProperty('fragment')) {
                $href .= '#' . trim($page->getProperty('fragment'), '#');
            }

            $e->setResult($href);
        }
    }
}