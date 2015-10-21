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
        $definition   = $container->findDefinition('task_runner');
        $taskServices = $container->findTaggedServiceIds('task');

        $configuredTasks = $container->getParameter('tasks');

        foreach ($taskServices as $id => $tags) {
            if (!in_array($id, $configuredTasks)) {
                continue;
            }
            $definition->addMethodCall('addTask', [new Reference($id)]);
        }
    }
}
