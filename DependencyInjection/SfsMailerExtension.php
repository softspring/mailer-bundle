<?php

namespace Softspring\MailerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class SfsMailerExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $container->setParameter('sfs_mailer.mailer', $config['mailer']);
        $container->setParameter('sfs_mailer.templates', $config['templates'] ?? []);

        $container->setParameter('sfs_mailer.from_email.sender_name', $config['from_email']['sender_name'] ?? null);
        $container->setParameter('sfs_mailer.from_email.address', $config['from_email']['address'] ?? null);

        $loader->load('services.yaml');
    }
}