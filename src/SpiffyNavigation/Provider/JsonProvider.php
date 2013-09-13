<?php

namespace SpiffyNavigation\Provider;

use SpiffyNavigation\Container;

class JsonProvider extends ArrayProvider
{
    /**
     * {@inheritDoc}
     */
    public function getContainer()
    {
        if ($this->container instanceof Container) {
            return $this->container;
        }

        if (!isset($this->options['json'])) {
            throw new \RuntimeException('Cannot build container: missing json in options');
        }

        $this->options['config'] = json_decode($this->options['json'], true);
        return parent::getContainer();
    }
}