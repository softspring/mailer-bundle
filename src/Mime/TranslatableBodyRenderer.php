<?php

namespace Softspring\MailerBundle\Mime;

use Symfony\Component\Mime\BodyRendererInterface;
use Symfony\Component\Mime\Message;
use Symfony\Contracts\Translation\LocaleAwareInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class TranslatableBodyRenderer implements BodyRendererInterface
{
    /**
     * @var BodyRendererInterface
     */
    protected $bodyRenderer;

    /**
     * @var TranslatorInterface|LocaleAwareInterface
     */
    protected $translator;

    /**
     * TranslatableBodyRenderer constructor.
     *
     * @param BodyRendererInterface                    $bodyRenderer
     * @param LocaleAwareInterface|TranslatorInterface $translator
     */
    public function __construct(BodyRendererInterface $bodyRenderer, $translator)
    {
        $this->bodyRenderer = $bodyRenderer;
        $this->translator = $translator;
    }

    /**
     * @inheritDoc
     */
    public function render(Message $message): void
    {
        try {
            if ($message instanceof TranslatableEmail && $message->getLocale()) {
                $storedLocale = $this->translator->getLocale();
                $this->translator->setLocale($message->getLocale());
            }

            $this->bodyRenderer->render($message);
        } catch (\Exception $e) {
            throw $e;
        } finally {
            if (isset($storedLocale)) {
                $this->translator->setLocale($storedLocale);
            }
        }
    }
}