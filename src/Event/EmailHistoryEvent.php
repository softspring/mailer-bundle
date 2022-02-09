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
