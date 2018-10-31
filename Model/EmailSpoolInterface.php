<?php

namespace Softspring\MailerBundle\Model;

use Swift_Mime_SimpleMessage;

interface EmailSpoolInterface
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
    public function getMessage(): Swift_Mime_SimpleMessage;

    /**
     * @param Swift_Mime_SimpleMessage $message
     *
     * @return bool
     */
    public function setMessage(Swift_Mime_SimpleMessage $message): bool;

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
}