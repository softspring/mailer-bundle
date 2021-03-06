<?php

namespace Softspring\MailerBundle\DependencyInjection;

use Softspring\MailerBundle\Model\EmailHistory;
use Softspring\MailerBundle\Model\EmailHistoryInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
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
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/services'));

        $container->setParameter('sfs_mailer.entity_manager_name', $config['entity_manager']);

        $container->setParameter('sfs_mailer.templates', $config['templates'] ?? []);

        $container->setParameter('sfs_mailer.from_email.sender_name', $config['from_email']['sender_name'] ?? null);
        $container->setParameter('sfs_mailer.from_email.address', $config['from_email']['address'] ?? null);

        $loader->load('services.yaml');

        if ($config['history']['enabled'] ?? false) {
            if (!in_array(EmailHistoryInterface::class, class_implements($config['history']['class']))) {
                throw new InvalidConfigurationException('sfs_mailer.history.class must implements '.EmailHistoryInterface::class);
            }

            $container->setParameter('sfs_mailer.history.load_default_mapping', true);
            $container->setParameter('sfs_mailer.history.class', $config['history']['class']);
            // $loader->load('history.yaml');
        }

        if ($config['deliver_every_mail_to']) {
            $container->setParameter('sfs_mailer.deliver_every_mail_to', $config['deliver_every_mail_to']);
            $loader->load('deliver_every_mail_to.yaml');
        }
    }

    public function prepend(ContainerBuilder $container)
    {
        $doctrineConfig = [];

        // add a default config to force load target_entities, will be overwritten by ResolveDoctrineTargetEntityPass
        $doctrineConfig['orm']['resolve_target_entities'][EmailHistoryInterface::class] = EmailHistory::class;

        // disable auto-mapping for this bundle to prevent mapping errors
        $doctrineConfig['orm']['mappings']['SfsMailerBundle'] = [
            'is_bundle' => true,
            'mapping' => false,
        ];

        $container->prependExtensionConfig('doctrine', $doctrineConfig);
    }
}