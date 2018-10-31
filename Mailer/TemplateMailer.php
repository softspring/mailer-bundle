<?php

namespace Softspring\MailerBundle\Mailer;

use Softspring\MailerBundle\Exception\InvalidTemplateException;
use Softspring\MailerBundle\Exception\TemplateRenderException;
use Softspring\MailerBundle\Loader\TemplateLoaderInterface;
use Softspring\MailerBundle\Model\TemplateCollection;
use Softspring\MailerBundle\Render\TemplateRender;

class TemplateMailer
{
    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var TemplateCollection
     */
    protected $templateCollection;

    /**
     * @var TemplateRender
     */
    protected $renderer;

    /**
     * @var string|null
     */
    protected $defaultFromEmail;

    /**
     * @var string|null
     */
    protected $defaultFromName;

    /**
     * TemplateMailer constructor.
     *
     * @param \Swift_Mailer             $mailer
     * @param TemplateLoaderInterface[] $templateLoaders
     * @param TemplateRender            $renderer
     * @param null|string               $defaultFromEmail
     * @param null|string               $defaultFromName
     */
    public function __construct(\Swift_Mailer $mailer, array $templateLoaders, TemplateRender $renderer, ?string $defaultFromEmail, ?string $defaultFromName)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
        $this->defaultFromEmail = $defaultFromEmail;
        $this->defaultFromName = $defaultFromName;

        $this->loadTemplates($templateLoaders);
    }

    /**
     * @return TemplateCollection
     */
    public function getTemplateCollection(): TemplateCollection
    {
        return $this->templateCollection;
    }

    /**
     * @param string      $templateId
     * @param string      $locale
     * @param array|null  $context
     * @param string      $toEmail
     * @param null|string $toName
     * @param null|string $fromEmail
     * @param null|string $fromName
     *
     * @throws InvalidTemplateException
     * @throws TemplateRenderException
     */
    public function send(string $templateId, string $locale, ?array $context, string $toEmail, ?string $toName, ?string $fromEmail, ?string $fromName)
    {
        $template = $this->templateCollection->getTemplate($templateId);

        if ($context === null) {
            $context = $template->getExampleContext();
        }

        if (!$template || !$this->renderer->templateExists($template)) {
            throw new InvalidTemplateException(sprintf('Template %s was not found.', $template->getTwigTemplate()));
        }


        if (!$this->renderer->hasSubjectBlock($template)) {
            throw new InvalidTemplateException(sprintf('Template %s has not subject block (named by configuration as %s).', $template->getTwigTemplate(), $template->getSubjectBlockName()));
        }

        $hasHtml = $this->renderer->hasHtmlBlock($template);
        $hasText = $this->renderer->hasTextBlock($template);

        if (!$hasHtml && !$hasText) {
            throw new InvalidTemplateException(sprintf('Template %s has not html or text blocks (named by configuration as %s %s respectively).', $template->getTwigTemplate(), $template->getHtmlBlockName()), $template->getTextBlockName());
        }

        $fromEmail = $fromEmail ?? $template->getFromEmail() ?? $this->defaultFromEmail;
        $fromName = $fromName ?? $template->getFromName() ?? $this->defaultFromName;

        $subject = $this->renderer->renderSubject($template, $context, $locale);

        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom($fromEmail, $fromName)
            ->setTo($toEmail, $toName);

        if ($hasHtml) {
            $htmlBody = $this->renderer->renderHtml($template, $context, $locale);
            $message->setBody($htmlBody, 'text/html');
        }

        if ($hasText) {
            $textBody = $this->renderer->renderText($template, $context, $locale);

            if ($hasHtml) {
                $message->addPart($textBody, 'text/plain');
            } else {
                $message->setBody($textBody);
            }
        }

        $this->mailer->send($message);
    }

    /**
     * @param TemplateLoaderInterface[] $templateLoaders
     */
    protected function loadTemplates(array $templateLoaders)
    {
        $this->templateCollection = new TemplateCollection();

        foreach ($templateLoaders as $loader) {
            $templateCollection = $loader->load();
            $this->templateCollection->appendCollection($templateCollection);
        }
    }
}