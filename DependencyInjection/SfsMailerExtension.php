<?php

namespace Softspring\MailerBundle\DependencyInjection;

use Softspring\MailerBundle\Model\EmailSpoolInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
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

        if ($config['spool']['enabled'] ?? false) {
            if (!in_array(EmailSpoolInterface::class, class_implements($config['spool']['entity_class']))) {
                throw new InvalidConfigurationException('sfs_mailer.spool.entity_class must implements '.EmailSpoolInterface::class);
            }

            $container->setParameter('sfs_mailer.spool.entity_class', $config['spool']['entity_class']);
            $container->setParameter('sfs_mailer.spool.remove_sent', $config['spool']['remove_sent']);
            $container->setParameter('sfs_mailer.spool.remove_failed', $config['spool']['remove_failed']);

            $loader->load('spool.yaml');
        }
    }
}