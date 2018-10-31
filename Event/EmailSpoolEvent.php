<?php

namespace Softspring\MailerBundle\Event;

use Softspring\MailerBundle\Model\EmailSpoolInterface;
use Symfony\Component\EventDispatcher\Event;

class EmailSpoolEvent extends Event
{
    /**
     * @var EmailSpoolInterface
     */
    protected $emailSpool;

    /**
     * EmailSpoolEvent constructor.
     *
     * @param EmailSpoolInterface $emailSpool
     */
    public function __construct(EmailSpoolInterface $emailSpool)
    {
        $this->emailSpool = $emailSpool;
    }

    /**
     * @return EmailSpoolInterface
     */
    public function getEmailSpool(): EmailSpoolInterface
    {
        return $this->emailSpool;
    }
}