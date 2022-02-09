<?php

namespace Softspring\MailerBundle\Template;

class TemplateCollection
{
    /**
     * @var Template[]
     */
    protected $templates;

    /**
     * TemplatesCollection constructor.
     */
    public function __construct()
    {
        $this->templates = [];
    }

    public function addTemplate(Template $template)
    {
        $this->templates[$template->getId()] = $template;
    }

    /**
     * @return Template[]
     */
    public function getTemplates(): array
    {
        return $this->templates;
    }

    /**
     * @param $id
     */
    public function getTemplate($id): ?Template
    {
        return $this->templates[$id] ?? null;
    }

    public function appendCollection(TemplateCollection $collection)
    {
        foreach ($collection->getTemplates() as $key => $template) {
            $this->templates[$key] = $template;
        }
    }
}
