<?php

namespace Softspring\MailerBundle\Loader;

use Softspring\MailerBundle\Model\Template;
use Softspring\MailerBundle\Model\TemplateCollection;

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
            $template->setTwigTemplate($templateConfig['template']);
            $template->setSubjectBlockName($templateConfig['subject_block']);
            $template->setHtmlBlockName($templateConfig['html_block']);
            $template->setTextBlockName($templateConfig['text_block']);
            $template->setExampleContext($templateConfig['example_context']);

            $collection->addTemplate($template);
        }

        return $collection;
    }
}