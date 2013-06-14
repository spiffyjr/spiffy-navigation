<?php

namespace SpiffyNavigation\View\Helper;

use InvalidArgumentException;
use SpiffyNavigation\Container;
use SpiffyNavigation\Service\Navigation;
use Zend\View\Helper\AbstractHtmlElement;

abstract class AbstractHelper extends AbstractHtmlElement
{
    /**
     * @var Navigation
     */
    protected $navigation;

    /**
     * Current container having work done.
     * @var string
     */
    protected $container = null;

    /**
     * Partial view script to use.
     * @var string
     */
    protected $partial = null;

    /**
     * @param \SpiffyNavigation\Service\Navigation $navigation
     */
    public function __construct(Navigation $navigation)
    {
        $this->navigation = $navigation;
    }

    /**
     * @param string|Container $container
     * @return AbstractHelper
     */
    public function __invoke($container = null)
    {
        if ($container) {
            $this->container = $this->getContainer($container);
        }
        return $this;
    }

    /**
     * Gets container from input.
     *
     * @param string|Container $input
     * @return Container
     * @throws InvalidArgumentException on invalid input.
     */
    protected function getContainer($input)
    {
        $input = $input ? $input : $this->container;

        if (is_string($input)) {
            return $this->navigation->getContainer($input);
        } else if (!$input instanceof Container) {
            throw new InvalidArgumentException('Container must be a string or instance of SpiffyNavigation\Container');
        }
        return $input;
    }

    /**
     * Cleans array of attributes based on valid input.
     *
     * @param array $input
     * @param array $valid
     * @return array
     */
    protected function cleanAttribs(array $input, array $valid)
    {
        foreach($input as $key => $value) {
            if (preg_match('/^data-(.+)/', $key) || in_array($key, $valid)) {
                continue;
            }
            unset($input[$key]);
        }

        return $input;
    }

    /**
     * Sets which partial view script to use.
     *
     * @param  string $partial partial view script or null
     * @return AbstractHelper
     */
    public function setPartial($partial)
    {
        if (null === $partial || is_string($partial)) {
            $this->partial = $partial;
        }

        return $this;
    }

    /**
     * Returns partial view script to use for rendering menu
     *
     * @return string|null
     */
    public function getPartial()
    {
        return $this->partial;
    }
}