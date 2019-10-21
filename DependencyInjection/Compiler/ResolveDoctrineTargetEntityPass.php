<?php

namespace Softspring\MailerBundle\DependencyInjection\Compiler;

use Softspring\CoreBundle\DependencyInjection\Compiler\AbstractResolveDoctrineTargetEntityPass;
use Softspring\MailerBundle\Model\EmailSpoolInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ResolveDoctrineTargetEntityPass extends AbstractResolveDoctrineTargetEntityPass
{
    /**
     * @inheritDoc
     */
    protected function getEntityManagerName(ContainerBuilder $container): string
    {
        return $container->getParameter('sfs_mailer.entity_manager_name');
    }

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $this->setTargetEntityFromParameter('sfs_mailer.spool.class', EmailSpoolInterface::class, $container, false);
    }
}