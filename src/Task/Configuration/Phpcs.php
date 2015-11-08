<?php
namespace QualityChecker\Task\Configuration;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Class Phpcs
 *
 * @package QualityChecker\Task\Configuration
 */
class Phpcs implements ConfigurationInterface
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
                    ->cannotBeEmpty()
                    ->isRequired()
                ->end()
                ->booleanNode('show_warnings')
                ->end()
                ->integerNode('tab_width')
                ->end()
                ->arrayNode('ignore_patterns')
                ->end()
                ->arrayNode('sniffs')
                ->end()
                ->integerNode('timeout')
                ->end();

        return $treeBuilder;
    }
}