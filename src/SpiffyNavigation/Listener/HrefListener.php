<?php

namespace SpiffyNavigation\Listener;

use RuntimeException;
use SpiffyNavigation\NavigationEvent;
use SpiffyNavigation\Service\Navigation;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

class HrefListener implements ListenerAggregateInterface
{
    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(Navigation::EVENT_GET_HREF, array($this, 'getHref'));
    }

    /**
     * Detach all previously attached listeners
     *
     * @param EventManagerInterface $events
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

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