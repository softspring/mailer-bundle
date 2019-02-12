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
        if ($container->hasParameter('sfs_mailer.spool.class')) {
            $emailSpoolClass = $container->getParameter('sfs_mailer.spool.class');

            if (!class_implements($emailSpoolClass, EmailSpoolInterface::class)) {
                throw new LogicException(sprintf('%s class must implements %s interface', $emailSpoolClass, EmailSpoolInterface::class));
            }
            $this->setTargetEntity($container, EmailSpoolInterface::class, $emailSpoolClass);
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