<?php

namespace Softspring\MailerBundle\Event;

use Softspring\MailerBundle\Model\EmailHistoryInterface;
use Symfony\Contracts\EventDispatcher\Event;

class EmailHistoryEvent extends Event
{
    /**
     * @var EmailHistoryInterface
     */
    protected $emailHistory;

    /**
     * EmailHistoryEvent constructor.
     *
     * @param EmailHistoryInterface $emailHistory
     */
    public function __construct(EmailHistoryInterface $emailHistory)
    {
        $this->emailHistory = $emailHistory;
    }

    /**
     * @return EmailHistoryInterface
     */
    public function getEmailHistory(): EmailHistoryInterface
    {
        return $this->emailHistory;
    }
}