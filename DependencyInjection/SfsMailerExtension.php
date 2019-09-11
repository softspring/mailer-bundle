<?php

namespace Softspring\MailerBundle\DependencyInjection;

use Softspring\MailerBundle\Model\EmailSpool;
use Softspring\MailerBundle\Model\EmailSpoolInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class SfsMailerExtension extends Extension implements PrependExtensionInterface
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
            if (!in_array(EmailSpoolInterface::class, class_implements($config['spool']['class']))) {
                throw new InvalidConfigurationException('sfs_mailer.spool.class must implements '.EmailSpoolInterface::class);
            }

            $container->setParameter('sfs_mailer.spool.load_default_mapping', true);

            $container->setParameter('sfs_mailer.spool.class', $config['spool']['class']);
            $container->setParameter('sfs_mailer.spool.remove_sent', $config['spool']['remove_sent']);
            $container->setParameter('sfs_mailer.spool.remove_failed', $config['spool']['remove_failed']);

            $loader->load('spool.yaml');
        }

        if ($config['auto_send']) {
            $loader->load('auto_email_sender.yaml');
        }
    }

    public function prepend(ContainerBuilder $container)
    {
        $doctrineConfig = [];

        // add a default config to force load target_entities, will be overwritten by ResolveDoctrineTargetEntityPass
        $doctrineConfig['orm']['resolve_target_entities'][EmailSpoolInterface::class] = EmailSpool::class;

        // disable auto-mapping for this bundle to prevent mapping errors
        $doctrineConfig['orm']['mappings']['SfsMailerBundle'] = [
            'is_bundle' => true,
            'mapping' => false,
        ];

        $container->prependExtensionConfig('doctrine', $doctrineConfig);
    }
}