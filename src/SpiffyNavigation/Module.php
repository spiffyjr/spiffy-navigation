<?php

namespace SpiffyNavigation;

use Zend\Mvc\MvcEvent;
use SpiffyNavigation\View\HelperConfig;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $app = $e->getApplication();
        $sm  = $app->getServiceManager();

        $config = new HelperConfig();
        $config->configureServiceManager($sm->get('ViewHelperManager'));
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'SpiffyNavigation\Service\Navigation' => 'SpiffyNavigation\Service\NavigationFactory'
            )
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }
}