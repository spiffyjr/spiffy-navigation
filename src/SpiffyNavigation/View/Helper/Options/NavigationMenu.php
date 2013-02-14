<?php

namespace SpiffyNavigation\View\Helper\Options;

use Zend\Stdlib\AbstractOptions;

class NavigationMenu extends AbstractOptions
{
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
}