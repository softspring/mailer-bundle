<?php

namespace Softspring\MailerBundle;

use Softspring\MailerBundle\DependencyInjection\Compiler\MailerServiceCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SfsMailerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new MailerServiceCompilerPass());
    }
}