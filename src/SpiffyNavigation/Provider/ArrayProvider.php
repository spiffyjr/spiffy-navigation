<?php

namespace SpiffyNavigation\Provider;

use SpiffyNavigation\Container;

class ArrayProvider extends AbstractProvider
{
    /**
     * {@inheritDoc}
     */
    public function getContainer()
    {
        $container = new Container();
    }
}