<?php

namespace QualityChecker\Task\Configuration;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Class PhpunitConfiguration
 *
 * @package QualityChecker\Task\Configuration
 */
class PhpunitConfiguration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('phpunit');

        $rootNode
            ->children()
                ->scalarNode('coverage-clover')->end()
                ->scalarNode('coverage-crap4j')->end()
                ->scalarNode('coverage-html')->end()
                ->scalarNode('coverage-php')->end()
                ->scalarNode('coverage-text')->end()
                ->scalarNode('coverage-xml')->end()
                ->scalarNode('log-junit')->end()
                ->scalarNode('log-tap')->end()
                ->scalarNode('log-json')->end()
                ->scalarNode('testdox-html')->end()
                ->scalarNode('testdox-text')->end()
                ->scalarNode('filter')->end()
                ->scalarNode('testsuite')->end()
                ->arrayNode('group')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('exclude-group')
                    ->prototype('scalar')->end()
                ->end()
                ->booleanNode('list-groups')->end()
                ->arrayNode('test-suffix')
                    ->prototype('scalar')->end()
                ->end()
                ->booleanNode('report-useless-tests')->end()
                ->booleanNode('strict-coverage')->end()
                ->booleanNode('strict-global-state')->end()
                ->booleanNode('disallow-test-output')->end()
                ->booleanNode('enforce-time-limit')->end()
                ->booleanNode('disallow-todo-tests')->end()
                ->booleanNode('process-isolation')->end()
                ->booleanNode('no-globals-backup')->end()
                ->booleanNode('static-backup')->end()
                ->enumNode('format')
                    ->isRequired()
                    ->values(['never', 'auto', 'always'])
                ->end()
                ->variableNode('columns')->end()
                ->booleanNode('stderr')->end()
                ->booleanNode('stop-on-error')->end()
                ->booleanNode('stop-on-failure')->end()
                ->booleanNode('stop-on-risky')->end()
                ->booleanNode('stop-on-skipped')->end()
                ->booleanNode('stop-on-incomplete')->end()
                ->booleanNode('verbose')->end()
                ->booleanNode('debug')->end()
                ->scalarNode('loader')->end()
                ->integerNode('repeat')
                    ->min(1)
                ->end()
                ->booleanNode('tap')->end()
                ->booleanNode('testdox')->end()
                ->scalarNode('printer')->end()
                ->scalarNode('bootstrap')->end()
                ->scalarNode('configuration')->end()
                ->booleanNode('no-configuration')->end()
                ->arrayNode('include-path')
                    ->prototype('scalar')->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
