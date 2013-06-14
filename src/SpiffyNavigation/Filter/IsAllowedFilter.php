<?php

namespace SpiffyNavigation\Filter;

use RecursiveFilterIterator;
use SpiffyNavigation\Container;
use SpiffyNavigation\Service\Navigation;

class IsAllowedFilter extends RecursiveFilterIterator
{
    /**
     * @param Container $iterator
     * @param Navigation $navigation
     */
    public function __construct(Container $iterator, Navigation $navigation)
    {
        $this->navigation = $navigation;
        parent::__construct($iterator);
    }

    /**
     * {@inheritDoc}
     */
    public function accept()
    {
        return $this->navigation->isAllowed($this->current());
    }

    /**
     * {@inheritDoc}
     */
    public function getChildren()
    {
        return new self($this->getInnerIterator()->getChildren(), $this->navigation);
    }
}