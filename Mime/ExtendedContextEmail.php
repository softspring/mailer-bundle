<?php

namespace Softspring\MailerBundle\Mime;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class ExtendedContextEmail extends TemplatedEmail
{
    /**
     * @var array
     */
    protected $context = [];

    public function getContext(): array
    {
        return array_merge($this->context, parent::getContext());
    }

    /**
     * @internal
     */
    public function __serialize(): array
    {
        return [$this->context, parent::__serialize()];
    }

    /**
     * @internal
     */
    public function __unserialize(array $data): void
    {
        [$this->context, $parentData] = $data;

        parent::__unserialize($parentData);
    }
}