<?php

namespace QualityChecker\Task\Configuration;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Class Phpmd
 *
 * @package QualityChecker\Task\Configuration
 */
class Phpmd implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('phpmd');

        $rootNode
            ->children()
                ->enumNode('format')
                    ->isRequired()
                    ->values(['xml', 'html', 'text'])
                ->end()
                ->arrayNode('paths')
                    ->isRequired()
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('rulesets')
                    ->isRequired()
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('suffixes')
                    ->prototype('scalar')->end()
                ->end()
                ->integerNode('timeout')
                    ->defaultValue(360)
                    ->min(0)
                ->end()
                ->booleanNode('strict')
                    ->defaultValue(false)
                ->end()
                ->scalarNode('reportfile')
                ->end()
                ->integerNode('minimumpriority')
                    ->min(0)
                ->end()
                ->arrayNode('exclude')
                    ->prototype('scalar')->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
