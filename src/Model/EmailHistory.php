<?php

namespace Softspring\MailerBundle\Model;

use Swift_Mime_SimpleMessage;

/**
 * Class EmailHistory.
 */
class EmailHistory implements EmailHistoryInterface
{
    /**
     * @var string|null
     */
    protected $id;

    /**
     * @var int|null
     */
    protected $status = EmailHistoryInterface::STATUS_PENDING;

    /**
     * @var string|null
     */
    protected $templateId;

    /**
     * @var Swift_Mime_SimpleMessage|null
     */
    protected $message;

    /**
     * @var int
     */
    protected $createdAt;

    /**
     * @var int
     */
    protected $lastStatusAt;

    /**
     * @return string
     */
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
        return trim($this->getMessage()->getSubject()) ?? null;
    }

    public function getMessageFrom(): ?array
    {
        return $this->getMessage()->getFrom() ?? null;
    }

    public function getMessageTo(): ?array
    {
        return $this->getMessage()->getTo() ?? null;
    }

    public function getMessageReplyTo(): ?string
    {
        return $this->getMessage()->getReplyTo() ?? null;
    }

    public function getMessageDate(): ?\DateTimeInterface
    {
        return $this->getMessage()->getDate() ?? null;
    }

    public function getMessageBody(): ?string
    {
        if (!$this->getMessage() instanceof Swift_Mime_SimpleMessage) {
            return null;
        }

        return $this->getMessage()->getBody();
    }

    public function getMessageBodyHtml(): ?string
    {
        if (!$this->getMessage() instanceof Swift_Mime_SimpleMessage) {
            return null;
        }

        if ('text/html' == $this->getMessage()->getBodyContentType()) {
            return $this->getMessage()->getBody();
        }

        return null;
    }

    public function getMessageBodyText(): ?string
    {
        if (!$this->getMessage() instanceof Swift_Mime_SimpleMessage) {
            return null;
        }

        if ('text/plain' == $this->getMessage()->getBodyContentType()) {
            return $this->getMessage()->getBody();
        }

        return null;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return \DateTime::createFromFormat('U', $this->createdAt) ?: null;
    }

    public function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt instanceof \DateTime ? $createdAt->format('U') : null;
    }

    public function getLastStatusAt(): ?\DateTime
    {
        return \DateTime::createFromFormat('U', $this->lastStatusAt) ?: null;
    }

    public function setLastStatusAt(?\DateTime $lastStatusAt): void
    {
        $this->lastStatusAt = $lastStatusAt instanceof \DateTime ? $lastStatusAt->format('U') : null;
    }
}
