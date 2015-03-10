<?php

namespace SimpleBus\SymfonyBridge\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

class EventBusExtension extends ConfigurableExtension
{
    public function __construct($alias)
    {
        $this->alias = $alias;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        return new EventBusConfiguration($this->getAlias());
    }

    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('event_bus.yml');

        $container->setAlias(
            'simple_bus.event_bus.event_name_resolver',
            'simple_bus.event_bus.' . $mergedConfig['event_name_resolver_strategy'] . '_event_name_resolver'
        );

        $container->setParameter('simple_bus.event_bus.logging.enabled', $mergedConfig['logging']['enabled']);
        $container->setParameter('simple_bus.event_bus.logging.channel', $mergedConfig['logging']['channel']);
    }
}
