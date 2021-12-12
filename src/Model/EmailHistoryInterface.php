<?php

namespace Softspring\MailerBundle\Model;

use Swift_Mime_SimpleMessage;

interface EmailHistoryInterface
{
    const STATUS_PENDING = 1;
    const STATUS_IN_PROGRESS = 2;
    const STATUS_SENT = 3;
    const STATUS_FAILED = 4;

    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return Swift_Mime_SimpleMessage
     */
    public function getMessage();

    /**
     * @param Swift_Mime_SimpleMessage $message
     *
     * @return bool
     */
    public function setMessage($message): bool;

    /**
     * @return int
     */
    public function getStatus(): int;

    /**
     * @param int $status
     */
    public function setStatus(int $status): void;

    /**
     * @return string
     */
    public function getTemplateId(): ?string;

    /**
     * @param string $templateId
     */
    public function setTemplateId(?string $templateId): void;

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime;

    /**
     * @param \DateTime|null $createdAt
     */
    public function setCreatedAt(?\DateTime $createdAt): void;
}
