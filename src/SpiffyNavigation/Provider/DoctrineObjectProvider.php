<?php

namespace SpiffyNavigation\Provider;

use Doctrine\Common\Persistence\ObjectManager;
use SpiffyNavigation\Container;

class DoctrineObjectProvider extends ArrayProvider
{
    /**
     * {@inheritDoc}
     */
    public function getContainer()
    {
        if ($this->container instanceof Container) {
            return $this->container;
        }

        $targetClass = isset($this->options['target_class']) ? $this->options['target_class'] : null;
        if (!$targetClass) {
            throw new \RuntimeException('Missing target_class');
        }

        $objectManager    = $this->getObjectManager();
        $objectRepository = $objectManager->getRepository($targetClass);

        $result = array();
        foreach ($objectRepository->findAll() as $page) {
            if (!$page instanceof ProviderEntityInterface) {
                throw new \RuntimeException('page must be an instance of ProviderEntityInterface');
            }
            if ($page->getParent()) {
                continue;
            }

            $result[] = $this->buildChildren($page);
        }

        $this->options['config'] = $result;
        return parent::getContainer();
    }

    protected function buildChildren(ProviderEntityInterface $page)
    {
        $result = array(
            'attributes' => $page->getAttributes(),
            'options'    => $page->getOptions()
        );
        foreach ($page->getPages() as $child) {
            $result['pages'][] = $this->buildChildren($child);
        }

        return $result;
    }

    /**
     * @throws \RuntimeException
     * @return ObjectManager
     */
    protected function getObjectManager()
    {
        if (!isset($this->options['object_manager']) || !$this->options['object_manager'] instanceof ObjectManager) {
            throw new \RuntimeException('Missing or invalid object_manager');
        }
        return $this->options['object_manager'];
    }
}