<?php

namespace SpiffyNavigation;

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
     * @var array
     */
    protected $providers = array();

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

    /**
     * @param array $providers
     * @return $this
     */
    public function setProviders($providers)
    {
        $this->providers = $providers;
        return $this;
    }

    /**
     * @return array
     */
    public function getProviders()
    {
        return $this->providers;
    }
}