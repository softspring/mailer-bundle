<?php

namespace Softspring\MailerBundle\Mime\Example;

use Softspring\MailerBundle\Mime\Example\Form\TranslatableEmailForm;
use Softspring\MailerBundle\Mime\ExampleInterface;
use Softspring\MailerBundle\Mime\TranslatableEmail;
use Symfony\Contracts\Translation\TranslatorInterface;

class TranslatableEmailExample implements ExampleInterface
{
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
            ->context($formData);
    }
}