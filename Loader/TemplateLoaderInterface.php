<?php

namespace Softspring\MailerBundle\Loader;

use Softspring\MailerBundle\Model\TemplateCollection;

interface TemplateLoaderInterface
{
    /**
     * @return TemplateCollection
     */
    public function load(): TemplateCollection;
}