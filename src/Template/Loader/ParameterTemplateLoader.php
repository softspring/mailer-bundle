<?php

namespace Softspring\MailerBundle\Template\Loader;

use Softspring\MailerBundle\Template\Template;
use Softspring\MailerBundle\Template\TemplateCollection;

class ParameterTemplateLoader implements TemplateLoaderInterface
{
    /**
     * @var array
     */
    protected $templatesConfig;

    /**
     * ParameterTemplateLoader constructor.
     */
    public function __construct(array $templatesConfig)
    {
        $this->templatesConfig = $templatesConfig;
    }

    public function load(): TemplateCollection
    {
        $collection = new TemplateCollection();

        $template = new Template();

        foreach ($this->templatesConfig as $templateKey => $templateConfig) {
            $template->setId($templateKey);
//            $template->setTwigTemplate($templateConfig['template']);
//            $template->setSubjectBlockName($templateConfig['subject_block']);
//            $template->setHtmlBlockName($templateConfig['html_block']);
//            $template->setTextBlockName($templateConfig['text_block']);
//            $template->setFromName($templateConfig['from_email']['sender_name']);
//            $template->setFromEmail($templateConfig['from_email']['address']);

            $collection->addTemplate($template);
        }

        return $collection;
    }
}
