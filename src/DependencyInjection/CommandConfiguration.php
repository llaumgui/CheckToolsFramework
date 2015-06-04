<?php
/*
 * This file is part of the CheckToolsFramework package.
 *
 * (c) Guillaume Kulakowski <guillaume@kulakowski.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Llaumgui\CheckToolsFramework\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * The Configuration class.
 */
class CommandConfiguration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('check_tools_framework');
        $rootNode
            ->children()
                ->arrayNode('default_commands')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->prototype('scalar')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
