<?php

namespace Softspring\MailerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TemplateLoadersCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $templateLoaderDefinition = $container->getDefinition('Softspring\MailerBundle\Template\TemplateLoader');

        // load templates
        $taggedServices = $container->findTaggedServiceIds('sfs_mailer.template_loader');

        $loaderReferences = [];
        foreach ($taggedServices as $id => $attributes) {
            $loaderReferences[] = new Reference($id);
        }

        $templateLoaderDefinition->setArgument('$templateLoaders', $loaderReferences);
    }
}