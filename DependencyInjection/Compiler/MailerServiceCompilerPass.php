<?php

namespace Softspring\MailerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class MailerServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $templateMailerDefinition = $container->getDefinition('Softspring\MailerBundle\Mailer\TemplateMailer');

        // set mailer service
        $mailerServiceName = $container->getParameter('sfs_mailer.mailer');
        $templateMailerDefinition->setArgument('$mailer', new Reference($mailerServiceName));

        // load templates
        $taggedServices = $container->findTaggedServiceIds('sfs_mailer.template_loader');

        $loaderReferences = [];
        foreach ($taggedServices as $id => $attributes) {
            $loaderReferences[] = new Reference($id);
        }

        $templateMailerDefinition->setArgument('$templateLoaders', $loaderReferences);
    }
}