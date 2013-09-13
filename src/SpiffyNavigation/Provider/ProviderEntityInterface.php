<?php

namespace SpiffyNavigation\Provider;

interface ProviderEntityInterface
{
    /**
     * @return integer
     */
    public function getId();

    /**
     * @return array
     */
    public function getAttributes();

    /**
     * @return array
     */
    public function getOptions();

    /**
     * @return ProviderEntityInterface[]
     */
    public function getPages();

    /**
     * @return ProviderEntityInterface
     */
    public function getParent();
}