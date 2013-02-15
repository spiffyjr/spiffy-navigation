<?php

namespace SpiffyNavigationTest;

use PHPUnit_Framework_TestCase;
use SpiffyNavigation\ContainerFactory;
use SpiffyNavigation\Page\PageFactory;
use SpiffyNavigation\Service\Navigation;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Renderer\PhpRenderer;


abstract class AbstractTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \SpiffyNavigation\View\Helper\AbstractHelper
     */
    protected $helper;

    /**
     * @var Navigation
     */
    protected $nav;

    /**
     * @var Container
     */
    protected $container1;

    /**
     * @var Container
     */
    protected $container2;

    /**
     * @var Container
     */
    protected $container3;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * Name of helper to be tested.
     * @var string
     */
    protected $helperName = null;

    public function setUp()
    {
        // setup containers from config
        $this->nav  = new Navigation();

        $this->container1 = ContainerFactory::create(include __DIR__ . '/_files/config/container1.php');
        $this->container2 = ContainerFactory::create(include __DIR__ . '/_files/config/container2.php');
        $this->container3 = ContainerFactory::create(include __DIR__ . '/_files/config/container3.php');

        $this->nav->addContainer('container1', $this->container1);
        $this->nav->addContainer('container2', $this->container2);
        $this->nav->addContainer('container3', $this->container3);

        // setup view
        $view = new PhpRenderer();
        $view->resolver()->addPath(__DIR__ . '/_files/views');

        // create helper
        if ($this->helperName) {
            $this->helper = new $this->helperName($this->nav);
            $this->helper->setView($view);
        }

        // setup service manager
        $smConfig = array(
            'modules'                 => array(),
            'module_listener_options' => array(
                'config_cache_enabled' => false,
                'cache_dir'            => 'data/cache',
            ),
        );

        $sm = $this->serviceManager = new ServiceManager(new ServiceManagerConfig);
        $sm->setService('ApplicationConfig', $smConfig);
        $sm->get('ModuleManager')->loadModules();
        $sm->get('Application')->bootstrap();

        /** @var $app \Zend\Mvc\Application */
        $app = $this->serviceManager->get('Application');
        $app->getMvcEvent()->setRouteMatch(new RouteMatch(array(
            'controller' => 'post',
            'action'     => 'view',
            'id'         => '1337',
        )));

        $sm->setAllowOverride(true);
    }

    protected function asset($name)
    {
        return file_get_contents(__DIR__ .'/_files/' . $name);
    }

    protected function setUpRouteForTreeMenu()
    {
        $routeMatch = new RouteMatch(array());
        $routeMatch->setMatchedRouteName('foo');
        $routeMatch->setParam('controller', 'foo');
        $routeMatch->setParam('action', 'list');

        $router = new TreeRouteStack();
        $router->addRoute('test', array(
            'type'    => 'Segment',
            'options' => array(
                'route'    => '/[:controller[/:action]]',
                'constraints' => array(
                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                ),
                'defaults' => array(
                    '__NAMESPACE__' => 'Application\Controller',
                ),
            ),
            'may_terminate' => true,
            'child_routes'  => array(
                'wildcard' => array(
                    'type' => 'wildcard'
                ),
            ),
        ));
        $this->nav->setRouter($router);
        /** @var $app \Zend\Mvc\Application */
        $app = $this->serviceManager->get('Application');
        $this->nav->setRouteMatch($routeMatch);
    }
}