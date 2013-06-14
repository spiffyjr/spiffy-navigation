<?php

namespace SpiffyNavigation\View\Helper;

use Zend\Stdlib\AbstractOptions;

class NavigationMenuOptions extends AbstractOptions
{
    /**
     * Class of the base ul element.
     * @var string
     */
    protected $ulClass = 'nav';

    /**
     * @param string $ulClass
     * @return $this
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