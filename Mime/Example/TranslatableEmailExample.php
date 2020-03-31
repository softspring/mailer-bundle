<?php

namespace Softspring\MailerBundle\Mime\Example;

use Softspring\MailerBundle\Mime\Example\Form\TranslatableEmailForm;
use Softspring\MailerBundle\Mime\TranslatableEmail;
use Symfony\Contracts\Translation\TranslatorInterface;

class TranslatableEmailExample implements ExampleInterface
{
    /**
     * @var string
     */
    protected $template;

    /**
     * TranslatableEmailExample constructor.
     *
     * @param string $template
     */
    public function __construct(string $template)
    {
        $this->template = $template;
    }

    public function getFormType(): string
    {
        return TranslatableEmailForm::class;
    }

    public function getEmptyData(): array
    {
        return [
        ];
    }

    public function getEmail(array $formData, TranslatorInterface $translator, string $locale): TranslatableEmail
    {
        return (new TranslatableEmail($translator, $locale))
            ->htmlTemplate($this->template)
            ->context($formData)
        ;
    }
}