<?php

namespace Softspring\MailerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sfs_mailer');

        $rootNode
            ->children()
                ->scalarNode('mailer')->defaultValue('swiftmailer.mailer')->cannotBeEmpty()->end()
                ->arrayNode('from_email')
                    ->children()
                        ->scalarNode('sender_name')->end()
                        ->scalarNode('address')->end()
                    ->end()
                ->end()

                ->arrayNode('templates')
                    ->useAttributeAsKey('key')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('template')->end()
                            ->scalarNode('subject_block')->defaultValue('subject')->end()
                            ->scalarNode('html_block')->defaultValue('html')->end()
                            ->scalarNode('text_block')->defaultValue('text')->end()
                            ->arrayNode('from_email')
                                ->children()
                                    ->scalarNode('sender_name')->end()
                                    ->scalarNode('address')->end()
                                ->end()
                            ->end()
                            ->arrayNode('example_context')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}