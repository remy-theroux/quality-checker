<?php

namespace QualityChecker\Configuration;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class ContainerFactory
 */
class ContainerFactory
{
    /**
     * Build application container & compile it
     *
     * @return ContainerBuilder
     */
    public static function compileConfiguration()
    {
        $container = new ContainerBuilder();
        $container->addCompilerPass(new QualityCheckerCompilerPass());

        // Load basic service file + custom user configuration
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('parameters.yml');
        $loader->load('services.yml');

        // Load qualitychecker.yml from current directory
        $configFilePath = getcwd() . DIRECTORY_SEPARATOR . '.qualitychecker.yml';
        $filesystem     = new Filesystem();
        if ($filesystem->exists($configFilePath)) {
            $loader->load($configFilePath);
        }

        $container->compile();

        return $container;
    }
}
