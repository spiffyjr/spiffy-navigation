<?php

namespace SpiffyNavigation;

use SpiffyNavigation\Service\Navigation;
use Zend\EventManager\Event;

class NavigationEvent extends Event
{
    /**
     * @var \SpiffyNavigation\Service\Navigation
     */
    protected $navigation;

    /**
     * @var mixed
     */
    protected $result;

    /**
     * @param Navigation $navigation
     * @return NavigationEvent
     */
    public function setNavigation(Navigation $navigation)
    {
        $this->navigation = $navigation;
        return $this;
    }

    /**
     * @return \SpiffyNavigation\Service\Navigation
     */
    public function getNavigation()
    {
        return $this->navigation;
    }

    /**
     * @param mixed $result
     * @return NavigationEvent
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }
}