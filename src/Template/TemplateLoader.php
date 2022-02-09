<?php

namespace Softspring\MailerBundle\Template;

use Softspring\MailerBundle\Template\Loader\TemplateLoaderInterface;

class TemplateLoader
{
    /**
     * @var TemplateCollection
     */
    protected $templateCollection;

    /**
     * TemplateMailer constructor.
     *
     * @param TemplateLoaderInterface[] $templateLoaders
     */
    public function __construct(array $templateLoaders)
    {
        $this->templateCollection = new TemplateCollection();

        foreach ($templateLoaders as $loader) {
            $templateCollection = $loader->load();
            $this->templateCollection->appendCollection($templateCollection);
        }
    }

    public function getTemplateCollection(): TemplateCollection
    {
        return $this->templateCollection;
    }
}
