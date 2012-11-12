<?php

namespace SpiffyNavigation\Service;

use InvalidArgumentException;
use RecursiveIteratorIterator;
use RuntimeException;
use SpiffyNavigation\Container;
use SpiffyNavigation\Page\PageInterface;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\RouteStackInterface;

class Navigation
{
    /**
     * Array of containers.
     * @var array
     */
    protected $containers = array();

    /**
     * Href cache using object hash.
     * @var array
     */
    protected $hrefCache = array();

    /**
     * Flag to set whether or not recursion should be enabled for isActive() checks.
     * Warning: modifying this value will invalidate the isActive() cache.
     * @var bool
     */
    protected $isActiveRecursion = true;

    /**
     * Active cache using object hash.
     * @var array
     */
    protected $isActiveCache = array();

    /**
     * Router used when assmebling href's from MVC.
     * @var RouteStackInterface
     */
    protected $router;

    /**
     * Route match used for determining active status.
     * @var RouteMatch
     */
    protected $routeMatch;

    /**
     * Check if a page is marked active.
     *
     * @param PageInterface $page
     * @return bool
     */
    public function isActive(PageInterface $page)
    {
        $hash = spl_object_hash($page);

        if (isset($this->isActiveCache[$hash])) {
            return $this->isActiveCache[$hash];
        }

        $active = false;
        if ($this->getRouteMatch()) {
            $attrs = $page->getAttributes();
            $name  = $this->getRouteMatch()->getMatchedRouteName();

            if (isset($attrs['route']) && $attrs['route'] == $name) {
                $active = true;
            } else if ($this->getIsActiveRecursion()) {
                $iterator = new RecursiveIteratorIterator($page, RecursiveIteratorIterator::CHILD_FIRST);

                /** @var \SpiffyNavigation\Page\Page $page */
                foreach ($iterator as $leaf) {
                    if ($this->isActive($leaf)) {
                        $active = true;
                        break;
                    }
                }
            }
        }

        $this->isActiveCache[$hash] = $active;
        return $active;
    }

    /**
     * Get the href for a page.
     *
     * @param PageInterface $page
     * @return string
     * @throws RuntimeException when an href can not be generated.
     */
    public function getHref(PageInterface $page)
    {
        $hash = spl_object_hash($page);

        if (isset($this->hrefCache[$hash])) {
            return $this->hrefCache[$hash];
        }

        $href  = null;
        $attrs = $page->getAttributes();
        if (isset($attrs['href'])) {
            $href = $attrs['href'];
        } else if (isset($attrs['uri'])) {
            $href = $attrs['uri'];
        } else if (isset($attrs['route'])) {
            if (!$this->getRouter()) {
                throw new RuntimeException('Cannot construct href from route with no router');
            }

            $params = isset($attrs['params']) ? $attrs['params'] : array();
            $href   = $this->getRouter()->assemble($params, array('name' => $attrs['route']));
        } else {
            throw new RuntimeException('Unable to construct href');
        }

        if (isset($attrs['fragment'])) {
            $href .= '#' . trim($attrs['fragment'], '#');
        }

        $this->hrefCache[$hash] = $href;
        return $href;
    }

    /**
     * Add a container to the stack.
     *
     * @param string $name
     * @param Container $container
     * @return Navigation
     * @throws InvalidArgumentException on duplicate container
     */
    public function addContainer($name, Container $container)
    {
        if ($this->hasContainer($name)) {
            throw new InvalidArgumentException(sprintf(
                'A container with name "%s" already exists.',
                $name
            ));
        }
        $this->containers[$name] = $container;
        return $this;
    }

    /**
     * Get a container by name.
     *
     * @param string $name
     * @return Container
     * @throws InvalidArgumentException on missing container
     */
    public function getContainer($name)
    {
        if (!$this->hasContainer($name)) {
            throw new InvalidArgumentException(sprintf(
                'No container with name "%s" could be found.',
                $name
            ));
        }
        return $this->containers[$name];
    }

    /**
     * Check if a container with name exists.
     *
     * @param string $name
     * @return bool
     */
    public function hasContainer($name)
    {
        return isset($this->containers[$name]);
    }

    /**
     * Get containers.
     *
     * @return array
     */
    public function getContainers()
    {
        return $this->containers;
    }

    /**
     * Remove a container from the stack.
     *
     * @param string $name
     * @return Navigation
     * @throws InvalidArgumentException on missing container
     */
    public function removeContainer($name)
    {
        if (!$this->hasContainer($name)) {
            throw new InvalidArgumentException(sprintf(
                'No container with name "%s" could be found.',
                $name
            ));
        }
        unset($this->containers[$name]);
        return $this;
    }

    /**
     * Clear containers.
     *
     * @return Navigation
     */
    public function clearContainers()
    {
        $this->containers = array();
        return $this;
    }

    /**
     * @param RouteMatch $routeMatch
     * @return Navigation
     */
    public function setRouteMatch($routeMatch)
    {
        $this->routeMatch = $routeMatch;
        return $this;
    }

    /**
     * @return RouteMatch
     */
    public function getRouteMatch()
    {
        return $this->routeMatch;
    }

    /**
     * @param \Zend\Mvc\Router\RouteStackInterface $router
     */
    public function setRouter($router)
    {
        $this->router = $router;
        return $this;
    }

    /**
     * @return \Zend\Mvc\Router\RouteStackInterface
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param boolean $isActiveRecursion
     * @return Navigation
     */
    public function setIsActiveRecursion($isActiveRecursion)
    {
        $this->isActiveRecursion = $isActiveRecursion;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsActiveRecursion()
    {
        return $this->isActiveRecursion;
    }
}