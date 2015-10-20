<?php

namespace QualityChecker\Configuration;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Filesystem\Filesystem;

final class ContainerFactory
{
    /**
     * @param string $configFilePath Path to qualitychecker configuration file
     *
     * @return ContainerBuilder
     */
    public static function buildFromConfiguration($configFilePath)
    {
        $container = new ContainerBuilder();
        $container->addCompilerPass(new QualityCheckerCompilerPass());

        // Load basic service file + custom user configuration
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../../resources/config'));
        $loader->load('services.yml');

        // Load qualitychecker.yml
        $filesystem = new Filesystem();
        if ($filesystem->exists($configFilePath)) {
            $loader->load($configFilePath);
        }

        $container->compile();

        return $container;
    }
}
