<?php

namespace SpiffyNavigation;

class Module
{
    public function getServiceConfig()
    {
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}