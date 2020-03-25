<?php

namespace Softspring\MailerBundle\Template\Loader;

use Softspring\MailerBundle\Template\TemplateCollection;

interface TemplateLoaderInterface
{
    /**
     * @return TemplateCollection
     */
    public function load(): TemplateCollection;
}