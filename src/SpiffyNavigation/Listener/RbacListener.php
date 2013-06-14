<?php

namespace SpiffyNavigation\Listener;

use SpiffyNavigation\NavigationEvent;
use SpiffyNavigation\Service\Navigation;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Permissions\Rbac\Rbac;

class RbacListener extends AbstractListenerAggregate
{
    /**
     * @var Rbac
     */
    protected $rbac;

    /**
     * @param Rbac $rbac
     */
    public function __construct(Rbac $rbac)
    {
        $this->rbac = $rbac;
    }

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(Navigation::EVENT_IS_ALLOWED, array($this, 'isAllowed'));
    }

    /**
     * @param NavigationEvent $event
     * @return bool
     */
    public function isAllowed(NavigationEvent $event)
    {
        /** @var \SpiffyNavigation\Page\Page $page */
        $page    = $event->getTarget();
        $options = $page->getOptions();

        if (isset($options['role']) && isset($options['permission'])) {
            if (isset($options['assertion'])) {
                return $this->rbac->isGranted($options['role'], $options['permission'], $options['assertion']);
            }
            return $this->rbac->isGranted($options['role'], $options['permission']);
        }
        return true;
    }
}