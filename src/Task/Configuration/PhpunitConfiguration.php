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
                ->integerNode('timeout')
                    ->defaultValue(360)
                    ->min(0)
                ->end()
            ->end();

        return $treeBuilder;
    }
}
