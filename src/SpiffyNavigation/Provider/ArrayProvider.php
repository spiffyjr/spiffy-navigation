<?php

namespace SpiffyNavigation\Provider;

use SpiffyNavigation\Container;
use SpiffyNavigation\Page\PageFactory;

class ArrayProvider extends AbstractProvider
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * {@inheritDoc}
     */
    public function getContainer()
    {
        if ($this->container instanceof Container) {
            return $this->container;
        }

        if (!isset($this->options['config'])) {
            throw new \RuntimeException('Cannot build container: missing config in options');
        }

        $this->container = new Container();
        foreach ($this->options['config'] as $page) {
            $this->container->addPage(PageFactory::create($page));
        }
        return $this->container;
    }
}