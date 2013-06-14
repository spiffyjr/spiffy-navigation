<?php

namespace SpiffyNavigation\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    /**
     * @var array
     */
    protected $containers = array();

    /**
     * @var array
     */
    protected $listeners = array();

    /**
     * @param array $containers
     * @return ModuleOptions
     */
    public function setContainers($containers)
    {
        $this->containers = $containers;
        return $this;
    }

    /**
     * @return array
     */
    public function getContainers()
    {
        return $this->containers;
    }

    /**
     * @param array $listeners
     * @return ModuleOptions
     */
    public function setListeners($listeners)
    {
        $this->listeners = $listeners;
        return $this;
    }

    /**
     * @return array
     */
    public function getListeners()
    {
        return $this->listeners;
    }
}