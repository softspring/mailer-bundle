<?php

namespace Softspring\MailerBundle\EventListener;

use Softspring\MailerBundle\Event\EmailSpoolEvent;
use Softspring\MailerBundle\SfsMailerEvents;
use Softspring\MailerBundle\Spool\SpoolManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class EmailSenderListener implements EventSubscriberInterface
{
    /**
     * @var SpoolManager
     */
    protected $spoolManager;

    /**
     * EmailSenderListener constructor.
     *
     * @param SpoolManager $spoolManager
     */
    public function __construct(SpoolManager $spoolManager)
    {
        $this->spoolManager = $spoolManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            SfsMailerEvents::EMAIL_SPOOL_QUEUED => [
                ['onSpoolQueued', 0],
            ],
            KernelEvents::TERMINATE => [
                ['onTerminateSendEmails', 0],
            ],
        ];
    }

    /**
     * @var boolean
     */
    protected $somethingToSend;

    /**
     * @param EmailSpoolEvent $event
     */
    public function onSpoolQueued(EmailSpoolEvent $event)
    {
        $this->somethingToSend = true;
    }

    /**
     * @param TerminateEvent $event
     */
    public function onTerminateSendEmails(TerminateEvent $event)
    {
        if (!$this->somethingToSend) {
            return;
        }

        $this->spoolManager->send();
    }
}