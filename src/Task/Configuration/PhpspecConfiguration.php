<?php

namespace QualityChecker\Task\Configuration;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Class PhpspecConfiguration
 *
 * @package QualityChecker\Task\Configuration
 */
class PhpspecConfiguration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('phpspec');

        $rootNode
            ->children()
                ->integerNode('timeout')
                    ->defaultValue(360)
                    ->min(0)
                ->end()
                ->scalarNode('config')->end()
                ->booleanNode('no-interaction')->end()
                ->booleanNode('verbose')->end()
            ->end();

        return $treeBuilder;
    }
}
