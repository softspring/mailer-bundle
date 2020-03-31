<?php

namespace Softspring\MailerBundle\Mime\Example;

use Softspring\MailerBundle\Mime\TranslatableEmail;
use Symfony\Contracts\Translation\TranslatorInterface;

interface ExampleInterface
{
    public function getFormType(): string;

    public function getEmptyData(): array;

    public function getEmail(array $formData, TranslatorInterface $translator, string $locale): TranslatableEmail;
}