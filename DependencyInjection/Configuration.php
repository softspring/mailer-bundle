<?php

namespace Softspring\MailerBundle\DependencyInjection;

use Softspring\MailerBundle\Entity\EmailSpool;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('sfs_mailer');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('entity_manager')
                    ->defaultValue('default')
                ->end()

                ->scalarNode('mailer')->defaultValue('swiftmailer.mailer')->cannotBeEmpty()->end()
                ->booleanNode('auto_send')->defaultFalse()->end()
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
                            ->arrayNode('example_context')->prototype('variable')->end()->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('spool')
                    ->canBeEnabled()
                    ->children()
                        ->scalarNode('class')->cannotBeEmpty()->defaultValue(EmailSpool::class)->end()
                        ->booleanNode('remove_sent')->defaultFalse()->end()
                        ->booleanNode('remove_failed')->defaultFalse()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}