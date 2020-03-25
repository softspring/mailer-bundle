<?php

namespace Softspring\MailerBundle\Template\Loader;

use Softspring\MailerBundle\Mime\Example\TranslatableEmailExample;
use Softspring\MailerBundle\Model\Template;
use Softspring\MailerBundle\Template\TemplateCollection;

class ParameterTemplateLoader implements TemplateLoaderInterface
{
    /**
     * @var array
     */
    protected $templatesConfig;

    /**
     * ParameterTemplateLoader constructor.
     *
     * @param array $templatesConfig
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
            // $template->setExample(new TranslatableEmailExample());
//            $template->setTwigTemplate($templateConfig['template']);
//            $template->setSubjectBlockName($templateConfig['subject_block']);
//            $template->setHtmlBlockName($templateConfig['html_block']);
//            $template->setTextBlockName($templateConfig['text_block']);
//            $template->setExampleContext($templateConfig['example_context']);
//            $template->setFromName($templateConfig['from_email']['sender_name']);
//            $template->setFromEmail($templateConfig['from_email']['address']);

            $collection->addTemplate($template);
        }

        return $collection;
    }
}