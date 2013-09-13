<?php

namespace SpiffyNavigation\Provider;

use SpiffyNavigation\Container;
use Zend\Config\Factory;

class ConfigProvider extends ArrayProvider
{
    /**
     * {@inheritDoc}
     */
    public function getContainer()
    {
        if ($this->container instanceof Container) {
            return $this->container;
        }

        if (!isset($this->options['file'])) {
            throw new \RuntimeException('Cannot build container: missing file in options');
        }

        $this->options['config'] = Factory::fromFile($this->options['file']);
        return parent::getContainer();
    }
}