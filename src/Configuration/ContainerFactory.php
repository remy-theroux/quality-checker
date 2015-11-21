<?php

namespace QualityChecker\Configuration;

use QualityChecker\Exception\ConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class ContainerFactory
 */
class ContainerFactory
{
    /** @const string Configuration filename */
    const CONFIG_FILE_NAME = '.qualitychecker.yml';

    /**
     * Build application container & compile it
     *
     * @return ContainerBuilder
     *
     * @throws ConfigurationException
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
        $currentDir     = getcwd();
        $configFilePath = $currentDir . DIRECTORY_SEPARATOR . self::CONFIG_FILE_NAME;
        $filesystem     = new Filesystem();
        if ($filesystem->exists($configFilePath)) {
            $loader->load($configFilePath);
        } else {
            $message = 'Can\'t find configuration file ' . self::CONFIG_FILE_NAME . ' in directory ' . $currentDir;
            throw new ConfigurationException($message);
        }

        $container->compile();

        return $container;
    }
}
