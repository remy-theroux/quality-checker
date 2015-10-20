<?php

namespace QualityChecker\Configuration;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class QualityCheckerCompilerPass
 *
 * @package QualityChecker\Configuration
 */
class QualityCheckerCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        // Get task runner & allowed services identifiers
        $definition     = $container->findDefinition('task_runner');
        $taggedServices = $container->findTaggedServiceIds('task');

        // Get configured tasks
        $configuration  = $container->getParameter('tasks');

        foreach ($taggedServices as $id => $tags) {
            $configKey = $this->locateConfigKey($tags);
            if (!array_key_exists($configKey, $configuration)) {
                continue;
            }

            $definition->addMethodCall('addTask', array(new Reference($id)));
        }
    }

    /**
     * @param $tags
     *
     * @return null|string
     */
    protected function locateConfigKey($tags)
    {
        foreach ($tags as $data) {
            if (isset($data['config'])) {
                return $data['config'];
            }
        }

        return null;
    }
}
