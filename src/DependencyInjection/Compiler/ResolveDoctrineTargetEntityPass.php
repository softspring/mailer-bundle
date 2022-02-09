<?php

namespace Softspring\MailerBundle\DependencyInjection\Compiler;

use Softspring\CoreBundle\DependencyInjection\Compiler\AbstractResolveDoctrineTargetEntityPass;
use Softspring\MailerBundle\Model\EmailHistoryInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ResolveDoctrineTargetEntityPass extends AbstractResolveDoctrineTargetEntityPass
{
    /**
     * {@inheritDoc}
     */
    protected function getEntityManagerName(ContainerBuilder $container): string
    {
        return $container->getParameter('sfs_mailer.entity_manager_name');
    }

    public function process(ContainerBuilder $container)
    {
        $this->setTargetEntityFromParameter('sfs_mailer.history.class', EmailHistoryInterface::class, $container, false);
    }
}
