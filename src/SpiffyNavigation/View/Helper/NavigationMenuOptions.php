<?php

namespace SpiffyNavigation\View\Helper;

use Zend\Stdlib\AbstractOptions;

class NavigationMenuOptions extends AbstractOptions
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
     * Attribute id of the base ul element.
     * @var string
     */
    protected $ulId = '';

    /**
     * Class of the sub ul element.
     * @var string
     */
    protected $ulSubClass = '';

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
     * @param string $ulId
     * @return NavigationMenu
     */
    public function setUlId($ulId)
    {
        $this->ulId = $ulId;
        return $this;
    }

    /**
     * @return string
     */
    public function getUlId()
    {
        return $this->ulId;
    }

    /**
     * @param string $ulSubClass
     * @return NavigationMenu
     */
    public function setUlSubClass($ulSubClass)
    {
        $this->ulSubClass = $ulSubClass;
        return $this;
    }

    /**
     * @return string
     */
    public function getUlSubClass()
    {
        return $this->ulSubClass;
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
}