<?php

namespace Softspring\MailerBundle\Model;

interface EmailHistoryInterface
{
    public const STATUS_PENDING = 1;
    public const STATUS_IN_PROGRESS = 2;
    public const STATUS_SENT = 3;
    public const STATUS_FAILED = 4;

    public function getId(): string;

    /**
     * @return \Swift_Mime_SimpleMessage
     */
    public function getMessage();

    /**
     * @param \Swift_Mime_SimpleMessage $message
     */
    public function setMessage($message): bool;

    public function getStatus(): int;

    public function setStatus(int $status): void;

    /**
     * @return string
     */
    public function getTemplateId(): ?string;

    /**
     * @param string $templateId
     */
    public function setTemplateId(?string $templateId): void;

    public function getCreatedAt(): ?\DateTime;

    public function setCreatedAt(?\DateTime $createdAt): void;
}
