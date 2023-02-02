<?php

namespace Softspring\MailerBundle\Template;

use Softspring\MailerBundle\Mime\TranslatableEmail;

class Template
{
    protected ?string $id = null;

    protected ?string $description = null;

    protected ?string $class = TranslatableEmail::class;

    protected bool $preview = false;

    public function __toString(): string
    {
        return $this->getId();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(?string $class): void
    {
        $this->class = $class;
    }

    public function isPreview(): bool
    {
        return $this->preview;
    }

    public function setPreview(bool $preview): void
    {
        $this->preview = $preview;
    }
}
