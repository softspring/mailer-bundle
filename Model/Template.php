<?php

namespace Softspring\MailerBundle\Model;

use Softspring\MailerBundle\Mime\ExampleInterface;
use Softspring\MailerBundle\Mime\TranslatableEmail;

class Template
{
    /**
     * @var string|null
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $class = TranslatableEmail::class;

    /**
     * @var ExampleInterface|null
     */
    protected $example;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getClass(): ?string
    {
        return $this->class;
    }

    /**
     * @param string|null $class
     */
    public function setClass(?string $class): void
    {
        $this->class = $class;
    }

    /**
     * @return ExampleInterface|null
     */
    public function getExample(): ?ExampleInterface
    {
        return $this->example;
    }

    /**
     * @param ExampleInterface|null $example
     */
    public function setExample(?ExampleInterface $example): void
    {
        $this->example = $example;
    }
}