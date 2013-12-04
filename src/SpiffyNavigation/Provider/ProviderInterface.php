<?php

namespace SpiffyNavigation\Provider;

interface ProviderInterface
{
    /**
     * @return \SpiffyNavigation\Container
     */
    public function getContainer();

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options);
}