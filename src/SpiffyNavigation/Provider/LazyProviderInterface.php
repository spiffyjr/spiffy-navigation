<?php

namespace SpiffyNavigation\Provider;

use SpiffyNavigation\LazyContainer;

interface LazyProviderInterface extends ProviderInterface
{
    /**
     * @param LazyContainer $container
     * @return array
     */
    public function getPages(LazyContainer $container);
} 