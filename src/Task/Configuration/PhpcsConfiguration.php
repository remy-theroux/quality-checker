<?php

namespace QualityChecker\Task\Configuration;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Class PhpcsConfiguration
 *
 * @package QualityChecker\Task\Configuration
 */
class PhpcsConfiguration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('phpcs');

        $rootNode
            ->children()
                ->scalarNode('standard')
                    ->defaultValue('PSR2')
                ->end()
                ->arrayNode('paths')
                    ->isRequired()
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('ignore_patterns')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('sniffs')
                    ->prototype('scalar')->end()
                ->end()
                ->booleanNode('show_warnings')
                    ->defaultValue(false)
                ->end()
                ->integerNode('tab_width')
                    ->min(0)
                ->end()
                ->integerNode('timeout')
                    ->defaultValue(540)
                    ->min(0)
                ->end()
            ->end();

        return $treeBuilder;
    }
}
