<?php

namespace Softspring\MailerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Softspring\MailerBundle\Model\EmailSpoolInterface;
use Swift_Mime_SimpleMessage;

/**
 * @ORM\MappedSuperclass()
 */
abstract class EmailSpoolSuperclass implements EmailSpoolInterface
{
    /**
     * @var string|null
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @var int|null
     * @ORM\Column(name="status", type="smallint", nullable=false, options={"unsigned":true})
     */
    protected $status = EmailSpoolInterface::STATUS_PENDING;

    /**
     * @var string|null
     * @ORM\Column(name="template_id", type="string", nullable=true)
     */
    protected $templateId;

    /**
     * @var Swift_Mime_SimpleMessage|null
     * @ORM\Column(name="message", type="text", nullable=false)
     */
    protected $message;

    /**
     * @return string
     */
    public function __toString()
    {
        return "{$this->getId()}";
    }

    /**
     * @inheritdoc
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getMessage(): Swift_Mime_SimpleMessage
    {
        return unserialize($this->message);
    }

    /**
     * @inheritdoc
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @inheritdoc
     */
    public function setMessage(Swift_Mime_SimpleMessage $message): bool
    {
        $this->message = serialize($message);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return null|string
     */
    public function getTemplateId(): ?string
    {
        return $this->templateId;
    }

    /**
     * @param null|string $templateId
     */
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

        if ($this->getMessage()->getBodyContentType() == 'text/html') {
            return $this->getMessage()->getBody();
        }

        return null;
    }

    public function getMessageBodyText(): ?string
    {
        if (!$this->getMessage() instanceof Swift_Mime_SimpleMessage) {
            return null;
        }

        if ($this->getMessage()->getBodyContentType() == 'text/plain') {
            return $this->getMessage()->getBody();
        }

        return null;
    }
}