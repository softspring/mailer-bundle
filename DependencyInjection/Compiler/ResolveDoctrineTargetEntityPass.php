<?php

namespace Softspring\MailerBundle\DependencyInjection\Compiler;

use Softspring\MailerBundle\Model\EmailSpoolInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\LogicException;

class ResolveDoctrineTargetEntityPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $historyConfig = $container->getParameter('sfs_user.history.config');
        if ($historyConfig['enabled']) {
            if (!class_implements($historyConfig['class'], EmailSpoolInterface::class)) {
                throw new LogicException(sprintf('%s class must implements %s interface', $historyConfig['class'], EmailSpoolInterface::class));
            }
            $this->setTargetEntity($container, EmailSpoolInterface::class, $historyConfig['class']);
        }
    }

    private function setTargetEntity(ContainerBuilder $container, string $interface, string $class)
    {
        $resolveTargetEntityListener = $container->findDefinition('doctrine.orm.listeners.resolve_target_entity');

        if (!$resolveTargetEntityListener->hasTag('doctrine.event_subscriber')) {
            $resolveTargetEntityListener->addTag('doctrine.event_subscriber');
        }

        $resolveTargetEntityListener->addMethodCall('addResolveTargetEntity', [$interface, $class, []]);
    }
}