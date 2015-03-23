<?php

namespace Renatomefi\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class NoExtension
 * @package Renatomefi\DependencyInjection
 * @codeCoverageIgnore
 */
class NoExtension extends Extension
{

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $this->processConfiguration($configuration, $configs);

        $reflection = new \ReflectionClass($this);
        $splFile = new \SplFileInfo($reflection->getFileName());

        $loader = new Loader\YamlFileLoader($container, new FileLocator($splFile->getPath() . '/../Resources/config'));
        $loader->load('services.yml');
    }
}