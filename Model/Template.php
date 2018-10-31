<?php

namespace Softspring\MailerBundle\Model;

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
    protected $twigTemplate;

    /**
     * @var string|null
     */
    protected $fromName;

    /**
     * @var string|null
     */
    protected $fromEmail;

    /**
     * @var string
     */
    protected $subjectBlockName = 'subject';

    /**
     * @var string
     */
    protected $htmlBlockName = 'html';

    /**
     * @var string
     */
    protected $textBlockName = 'text';

    /**
     * @var array
     */
    protected $exampleContext = [];

    /**
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param null|string $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return null|string
     */
    public function getTwigTemplate(): ?string
    {
        return $this->twigTemplate;
    }

    /**
     * @param null|string $twigTemplate
     */
    public function setTwigTemplate(?string $twigTemplate): void
    {
        $this->twigTemplate = $twigTemplate;
    }

    /**
     * @return null|string
     */
    public function getFromName(): ?string
    {
        return $this->fromName;
    }

    /**
     * @param null|string $fromName
     */
    public function setFromName(?string $fromName): void
    {
        $this->fromName = $fromName;
    }

    /**
     * @return null|string
     */
    public function getFromEmail(): ?string
    {
        return $this->fromEmail;
    }

    /**
     * @param null|string $fromEmail
     */
    public function setFromEmail(?string $fromEmail): void
    {
        $this->fromEmail = $fromEmail;
    }

    /**
     * @return string
     */
    public function getSubjectBlockName(): string
    {
        return $this->subjectBlockName;
    }

    /**
     * @param string $subjectBlockName
     */
    public function setSubjectBlockName(string $subjectBlockName): void
    {
        $this->subjectBlockName = $subjectBlockName;
    }

    /**
     * @return string
     */
    public function getHtmlBlockName(): string
    {
        return $this->htmlBlockName;
    }

    /**
     * @param string $htmlBlockName
     */
    public function setHtmlBlockName(string $htmlBlockName): void
    {
        $this->htmlBlockName = $htmlBlockName;
    }

    /**
     * @return string
     */
    public function getTextBlockName(): string
    {
        return $this->textBlockName;
    }

    /**
     * @param string $textBlockName
     */
    public function setTextBlockName(string $textBlockName): void
    {
        $this->textBlockName = $textBlockName;
    }

    /**
     * @return array
     */
    public function getExampleContext(): array
    {
        return $this->exampleContext;
    }

    /**
     * @param array $exampleContext
     */
    public function setExampleContext(array $exampleContext): void
    {
        $this->exampleContext = $exampleContext;
    }
}