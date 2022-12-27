<?php

namespace Softspring\MailerBundle\Model;

/**
 * Class EmailHistory.
 */
class EmailHistory implements EmailHistoryInterface
{
    protected ?string $id;

    protected ?int $status = EmailHistoryInterface::STATUS_PENDING;

    protected ?string $templateId;

    protected ?\Swift_Mime_SimpleMessage $message;

    protected int $createdAt;

    protected int $lastStatusAt;

    public function __toString()
    {
        return "{$this->getId()}";
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getMessage()
    {
        return unserialize($this->message);
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setMessage($message): bool
    {
        /* @phpstan-ignore-next-line */
        $this->message = serialize($message);

        return true;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getTemplateId(): ?string
    {
        return $this->templateId;
    }

    public function setTemplateId(?string $templateId): void
    {
        $this->templateId = $templateId;
    }

    public function getMessageSubject(): ?string
    {
        return trim($this->getMessage()->getSubject()) ?: null;
    }

    public function getMessageFrom(): ?array
    {
        return $this->getMessage()->getFrom() ?: null;
    }

    public function getMessageTo(): ?array
    {
        return $this->getMessage()->getTo() ?: null;
    }

    public function getMessageReplyTo(): ?string
    {
        return $this->getMessage()->getReplyTo() ?: null;
    }

    public function getMessageDate(): ?\DateTimeInterface
    {
        return $this->getMessage()->getDate();
    }

    public function getMessageBody(): ?string
    {
        if (!$this->getMessage() instanceof \Swift_Mime_SimpleMessage) {
            return null;
        }

        return $this->getMessage()->getBody();
    }

    public function getMessageBodyHtml(): ?string
    {
        if (!$this->getMessage() instanceof \Swift_Mime_SimpleMessage) {
            return null;
        }

        if ('text/html' == $this->getMessage()->getBodyContentType()) {
            return $this->getMessage()->getBody();
        }

        return null;
    }

    public function getMessageBodyText(): ?string
    {
        if (!$this->getMessage() instanceof \Swift_Mime_SimpleMessage) {
            return null;
        }

        if ('text/plain' == $this->getMessage()->getBodyContentType()) {
            return $this->getMessage()->getBody();
        }

        return null;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return \DateTime::createFromFormat('U', "{$this->createdAt}") ?: null;
    }

    public function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt instanceof \DateTime ? (int) $createdAt->format('U') : null;
    }

    public function getLastStatusAt(): ?\DateTime
    {
        return \DateTime::createFromFormat('U', "{$this->lastStatusAt}") ?: null;
    }

    public function setLastStatusAt(?\DateTime $lastStatusAt): void
    {
        $this->lastStatusAt = $lastStatusAt instanceof \DateTime ? (int) $lastStatusAt->format('U') : null;
    }
}
