<?php

namespace SpiffyNavigation\View;

use Zend\ServiceManager\ConfigInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

class HelperConfig implements ConfigInterface
{
    /**
     * @var array Pre-aliased view helpers
     */
    protected $helpers = array(
        'navigationBreadcrumbs' => 'SpiffyNavigation\View\Helper\NavigationBreadcrumbs',
        'navigationMenu'        => 'SpiffyNavigation\View\Helper\NavigationMenu',
        'navigationSitemap'     => 'SpiffyNavigation\View\Helper\NavigationSitemap',
    );

    /**
     * Configure the provided service manager instance with the configuration
     * in this class.
     *
     * Adds the invokables defined in this class to the SM managing helpers.
     *
     * @param  ServiceManager $serviceManager
     * @return void
     */
    public function configureServiceManager(ServiceManager $serviceManager)
    {
        foreach ($this->helpers as $name => $className) {
            $serviceManager->setFactory($name, function(ServiceLocatorInterface $sm) use ($className) {
                $class = new $className($sm->getServiceLocator()->get('SpiffyNavigation\Service\Navigation'));

                return $class;
            });
        }
    }
}