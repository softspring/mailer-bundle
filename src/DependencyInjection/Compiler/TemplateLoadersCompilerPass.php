<?php

declare(strict_types=1);

namespace Softspring\MailerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TemplateLoadersCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $templateLoaderDefinition = $container->getDefinition('Softspring\MailerBundle\Template\TemplateLoader');

        // load templates
        $taggedServices = $container->findTaggedServiceIds('sfs_mailer.template_loader');

        $loaderReferences = [];
        foreach (array_keys($taggedServices) as $id) {
            $loaderReferences[] = new Reference($id);
        }

        $templateLoaderDefinition->setArgument('$templateLoaders', $loaderReferences);
    }
}
