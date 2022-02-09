<?php

namespace Softspring\MailerBundle\Template\Loader;

use Softspring\MailerBundle\Template\TemplateCollection;

interface TemplateLoaderInterface
{
    public function load(): TemplateCollection;
}
