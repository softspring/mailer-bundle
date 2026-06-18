<?php

declare(strict_types=1);

namespace Softspring\MailerBundle\Event;

use Softspring\MailerBundle\Model\EmailHistoryInterface;
use Symfony\Contracts\EventDispatcher\Event;

class EmailHistoryEvent extends Event
{
    protected EmailHistoryInterface $emailHistory;

    /**
     * EmailHistoryEvent constructor.
     */
    public function __construct(EmailHistoryInterface $emailHistory)
    {
        $this->emailHistory = $emailHistory;
    }

    public function getEmailHistory(): EmailHistoryInterface
    {
        return $this->emailHistory;
    }
}
