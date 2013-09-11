<?php

namespace SpiffyNavigation\Provider;

use SpiffyNavigation\ContainerFactory;

class JsonProvider extends AbstractProvider
{
    /**
     * {@inheritDoc}
     */
    public function getContainer()
    {
        if (!isset($this->options['json'])) {
            throw new \RuntimeException('Cannot build container: missing json in options');
        }
        return ContainerFactory::create(json_decode($this->options['json'], true));
    }
}