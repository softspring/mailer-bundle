<?php

namespace Softspring\MailerBundle\Mime;

use Symfony\Contracts\Translation\TranslatorInterface;

interface ExampleInterface
{
    public function getFormType(): string;

    public function getEmptyData(): array;

    public function getEmail(array $formData, TranslatorInterface $translator, string $locale): TranslatableEmail;
}