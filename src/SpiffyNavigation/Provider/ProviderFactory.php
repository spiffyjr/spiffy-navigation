<?php

namespace SpiffyNavigation\Provider;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ProviderFactory implements FactoryInterface
{
    /**
     * @var array
     */
    protected $spec = array();

    /**
     * @var array
     */
    protected $classmap = array(
        'array'          => 'SpiffyNavigation\Provider\ArrayProvider',
        'config'         => 'SpiffyNavigation\Provider\ConfigProvider',
        'doctrineobject' => 'SpiffyNavigation\Provider\DoctrineObjectProvider',
        'json'           => 'SpiffyNavigation\Provider\JsonProvider',
    );

    /**
     * @param array $spec
     */
    public function __construct(array $spec)
    {
        $this->spec = $spec;
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @return ProviderInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $spec = $this->spec;

        if (!isset($spec['type'])) {
            throw new \InvalidArgumentException('Missing type for provider');
        }

        $type     = $spec['type'];
        $options  = isset($spec['options']) ? $spec['options'] : array();
        $class    = isset($this->classmap[strtolower($type)]) ? $this->classmap[strtolower($type)]:  null;
        $provider = null;

        if ($class) {
            if ($class === 'SpiffyNavigation\Provider\DoctrineObjectProvider') {
                if (!isset($options['object_manager']) || !$serviceLocator->has($options['object_manager'])) {
                    throw new \InvalidArgumentException(sprintf(
                        'Missing or invalid object_manager option: %s',
                        $options['object_manager']
                    ));
                }
                $options['object_manager'] = $serviceLocator->get($options['object_manager']);
            }

            $provider = new $class();
        } elseif ($serviceLocator->has($type)) {
            $provider = $serviceLocator->get($type);
        } elseif (class_exists($type)) {
            $provider = new $type();
        }

        if (!$provider instanceof ProviderInterface) {
            throw new \RuntimeException(sprintf(
                'Could not determine provider to create for "%s"',
                $type
            ));
        }

        $provider->setOptions($options);
        return $provider;
    }
}