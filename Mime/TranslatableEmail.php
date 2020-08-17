<?php

namespace Softspring\MailerBundle\Mime;

use Symfony\Component\Mime\Header\Headers;
use Symfony\Component\Mime\Part\AbstractPart;
use Symfony\Contracts\Translation\TranslatorInterface;

class TranslatableEmail extends ExtendedContextEmail
{
    /**
     * @var string|null
     */
    protected $locale;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    public static function generateExample(TranslatorInterface $translator, ?string $locale = null): TranslatableEmail
    {
        return new self($translator, $locale);
    }

    /**
     * TranslatableEmail constructor.
     *
     * @param TranslatorInterface $translator
     * @param string|null         $locale
     * @param Headers|null        $headers
     * @param AbstractPart|null   $body
     */
    public function __construct(TranslatorInterface $translator, ?string $locale = null,  Headers $headers = null, AbstractPart $body = null)
    {
        parent::__construct($headers, $body);
        $this->translator = $translator;
        $this->locale = $locale;
    }

    public function subject(string $subject, ?string $domain = null)
    {
        $subject = $this->translator->trans($subject, $this->getTranslationParams(), $domain, $this->locale);

        return parent::subject($subject);
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    /**
     * @param array $context
     *
     * @return $this
     */
    public function setTranslationParams(array $context): self
    {
        $this->setContextBlock('__translation_params', $context);

        return $this;
    }

    /**
     * @return array
     */
    public function getTranslationParams(): array
    {
        return $this->getContextBlock('__translation_params');
    }
}