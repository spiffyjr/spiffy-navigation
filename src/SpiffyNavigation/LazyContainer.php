<?php
/**
 * LazyContainer
 *
 * @category  SpiffyNavigation
 * @package   SpiffyNavigation
 * @copyright 2014 ACSI Holding bv (http://www.acsi.eu)
 * @version   SVN: $Id$
 */

namespace SpiffyNavigation;

use SpiffyNavigation\Provider\LazyProviderInterface;

class LazyContainer extends Container
{
    /**
     * @var bool
     */
    protected $initialized = false;

    /**
     * @var LazyProviderInterface
     */
    protected $provider;

    /**
     * {@inheritDoc}
     */
    public function rewind()
    {
        if (!$this->isInitialized()) {
            $this->initialize();
        }

        $this->index = 0;
    }

    public function __construct(LazyProviderInterface $provider)
    {
        $this->setProvider($provider);
    }

    /**
     * Calls the provider to fill the container
     */
    public function initialize()
    {
        $this->getProvider()->getPages($this);
        $this->setInitialized(true);
    }

    /**
     * @return boolean
     */
    public function isInitialized()
    {
        return $this->initialized;
    }

    /**
     * @param boolean $initialized
     */
    public function setInitialized($initialized)
    {
        if (!$initialized) {
            $this->children = array();
        }
        
        $this->initialized = $initialized;
    }

    /**
     * @return LazyProviderInterface
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param LazyProviderInterface $provider
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
    }
} 
