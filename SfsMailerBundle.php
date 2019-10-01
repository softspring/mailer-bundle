<?php

namespace Softspring\MailerBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Softspring\MailerBundle\DependencyInjection\Compiler\MailerServiceCompilerPass;
use Softspring\MailerBundle\DependencyInjection\Compiler\ResolveDoctrineTargetEntityPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SfsMailerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $basePath = realpath(__DIR__.'/Resources/config/doctrine-mapping/');

        $this->addRegisterMappingsPass($container, [$basePath.'/model' => 'Softspring\MailerBundle\Model'], 'sfs_mailer.spool.enabled');
        $this->addRegisterMappingsPass($container, [$basePath.'/entities' => 'Softspring\MailerBundle\Entity'], 'sfs_mailer.spool.load_default_mapping');

        $container->addCompilerPass(new ResolveDoctrineTargetEntityPass());
        $container->addCompilerPass(new MailerServiceCompilerPass());
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $mappings
     * @param string|bool      $enablingParameter
     */
    private function addRegisterMappingsPass(ContainerBuilder $container, array $mappings, $enablingParameter = false)
    {
        if (!class_exists('Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass')) {
            return;
        }

        $container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver($mappings, ['sfs_user_em'], $enablingParameter));
    }
}