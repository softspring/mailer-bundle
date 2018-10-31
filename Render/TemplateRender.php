<?php

namespace Softspring\MailerBundle\Render;

use Softspring\MailerBundle\Model\Template;
use Softspring\MailerBundle\Exception\TemplateRenderException;
use Symfony\Component\Translation\TranslatorInterface;

class TemplateRender
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * TemplateRender constructor.
     *
     * @param \Twig_Environment   $twig
     * @param TranslatorInterface $translator
     */
    public function __construct(\Twig_Environment $twig, TranslatorInterface $translator)
    {
        $this->twig = $twig;
        $this->translator = $translator;
    }

    /**
     * @param Template $template
     *
     * @return bool
     */
    public function templateExists(Template $template): bool
    {
        try {
            $this->twig->loadTemplate($template->getTwigTemplate());
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param Template $template
     *
     * @return bool
     */
    public function hasSubjectBlock(Template $template): bool
    {
        return $this->hasBlock($template->getSubjectBlockName(), $template->getTwigTemplate());
    }

    /**
     * @param Template $template
     *
     * @return bool
     */
    public function hasHtmlBlock(Template $template): bool
    {
        return $this->hasBlock($template->getHtmlBlockName(), $template->getTwigTemplate());
    }

    /**
     * @param Template $template
     *
     * @return bool
     */
    public function hasTextBlock(Template $template): bool
    {
        return $this->hasBlock($template->getTextBlockName(), $template->getTwigTemplate());
    }

    /**
     * @param Template $template
     * @param array    $context
     * @param string   $locale
     *
     * @return string
     * @throws TemplateRenderException
     */
    public function renderSubject(Template $template, array $context, string $locale): string
    {
        return $this->renderBlock($template->getSubjectBlockName(), $template->getTwigTemplate(), $context, $locale);
    }

    /**
     * @param Template $template
     * @param array    $context
     * @param string   $locale
     *
     * @return string
     * @throws TemplateRenderException
     */
    public function renderHtml(Template $template, array $context, string $locale): string
    {
        return $this->renderBlock($template->getHtmlBlockName(), $template->getTwigTemplate(), $context, $locale);
    }

    /**
     * @param Template $template
     * @param array    $context
     * @param string   $locale
     *
     * @return string
     * @throws TemplateRenderException
     */
    public function renderText(Template $template, array $context, string $locale): string
    {
        return $this->renderBlock($template->getTextBlockName(), $template->getTwigTemplate(), $context, $locale);
    }

    /**
     * @param string $block
     * @param string $template
     * @param array  $context
     * @param string $locale
     *
     * @return string
     * @throws TemplateRenderException
     */
    protected function renderBlock(string $block, string $template, array $context, string $locale): string
    {
        try {
            $oldLocale = $this->translator->getLocale();
            $this->translator->setLocale($locale);

            return $this->twig->loadTemplate($template)->renderBlock($block, $context);
        } catch (\Throwable $e) {
            $this->translator->setLocale($oldLocale);

            throw new TemplateRenderException('Error rendering mail template', 0, $e);
        }
    }

    /**
     * @param string $block
     * @param string $template
     *
     * @return bool
     */
    protected function hasBlock(string $block, string $template): bool
    {
        try {
            $twigTemplate = $this->twig->loadTemplate($template);

            return $twigTemplate->hasBlock($block, [], $twigTemplate->getBlocks());
        } catch (\Throwable $e) {
            return false;
        }
    }
}