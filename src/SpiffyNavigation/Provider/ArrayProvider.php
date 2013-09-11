<?php

namespace SpiffyNavigation\Provider;

use SpiffyNavigation\ContainerFactory;

class ArrayProvider extends AbstractProvider
{
    /**
     * {@inheritDoc}
     */
    public function getContainer()
    {
        if (!isset($this->options['config'])) {
            throw new \RuntimeException('Cannot build container: missing config in options');
        }
        return ContainerFactory::create($this->options['config']);
    }
}