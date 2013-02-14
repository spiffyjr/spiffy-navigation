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
     * @param $ulClass
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
     * @param int $minDepth
     */
    public function setMinDepth($minDepth)
    {
        $this->minDepth = $minDepth;
    }

    /**
     * @return int
     */
    public function getMinDepth()
    {
        return $this->minDepth;
    }

    /**
     * @param int $maxDepth
     */
    public function setMaxDepth($maxDepth)
    {
        $this->maxDepth = $maxDepth;
    }

    /**
     * @return int
     */
    public function getMaxDepth()
    {
        return $this->maxDepth;
    }

    /**
     * @param string $activeClass
     */
    public function setActiveClass($activeClass)
    {
        $this->activeClass = $activeClass;
    }

    /**
     * @return string
     */
    public function getActiveClass()
    {
        return $this->activeClass;
    }

}