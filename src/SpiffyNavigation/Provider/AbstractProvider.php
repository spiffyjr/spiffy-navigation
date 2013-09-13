<?php

namespace SpiffyNavigation\Provider;

abstract class AbstractProvider implements ProviderInterface
{
    /**
     * @var array
     */
    protected $options = array();

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }
}