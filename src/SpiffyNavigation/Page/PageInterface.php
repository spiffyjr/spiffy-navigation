<?php

namespace SpiffyNavigation\Page;

interface PageInterface
{
    public function getAttributes();

    public function setAttributes(array $attributes);
}