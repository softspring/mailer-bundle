<?php

namespace Softspring\MailerBundle\Template;

class TemplateCollection
{
    /**
     * @var Template[]
     */
    protected array $templates = [];

    public function addTemplate(Template $template): void
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

    public function getTemplate($id): ?Template
    {
        return $this->templates[$id] ?? null;
    }

    public function appendCollection(TemplateCollection $collection): void
    {
        foreach ($collection->getTemplates() as $key => $template) {
            $this->templates[$key] = $template;
        }
    }
}
