<?php

namespace SpiffyNavigation\View\Helper\Options;

use Zend\Stdlib\AbstractOptions;

class NavigationMenu extends AbstractOptions
{
    /**
     * @var int
     */
    protected $minDepth = -1;

    /**
     * @var int
     */
    protected $maxDepth = -1;

    /**
     * @var string
     */
    protected $activeClass = "active";

    /**
     * Class of the base ul element.
     * @var string
     */
    protected $ulClass = 'nav';

    /**
     * @var bool
     */
    protected $dropdown = false;

    /**
     * @param string $ulClass
     * @return NavigationMenu
     */
    public function setUlClass($ulClass)
    {
        $this->ulClass = $ulClass;
        return $this;
    }

    /**
     * @return string
     */
    public function getUlClass()
    {
        return $this->ulClass;
    }

    /**
     * @param $minDepth
     * @return NavigationMenu
     */
    public function setMinDepth($minDepth)
    {
        $this->minDepth = $minDepth;
        return $this;
    }

    /**
     * @return int
     */
    public function getMinDepth()
    {
        return $this->minDepth;
    }

    /**
     * @param $maxDepth
     * @return NavigationMenu
     */
    public function setMaxDepth($maxDepth)
    {
        $this->maxDepth = $maxDepth;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxDepth()
    {
        return $this->maxDepth;
    }

    /**
     * @param $activeClass
     * @return NavigationMenu
     */
    public function setActiveClass($activeClass)
    {
        $this->activeClass = $activeClass;
        return $this;
    }

    /**
     * @return string
     */
    public function getActiveClass()
    {
        return $this->activeClass;
    }

    /**
     * @param boolean $dropdown
     */
    public function setDropdown($dropdown)
    {
        $this->dropdown = $dropdown;
    }

    /**
     * @return boolean
     */
    public function getDropdown()
    {
        return $this->dropdown;
    }

}
