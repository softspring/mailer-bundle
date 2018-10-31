<?php

namespace Softspring\MailerBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Softspring\MailerBundle\Event\EmailSpoolEvent;
use Softspring\MailerBundle\Model\EmailSpoolInterface;
use Softspring\MailerBundle\SfsMailerEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MessageSendingEventListener implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * MessageSendingEventListener constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return [
            SfsMailerEvents::EMAIL_SPOOL_SENT => [['onEmailSpoolSending']]
        ];
    }

    public function onEmailSpoolSending(EmailSpoolEvent $event)
    {
        $emailSpool = $event->getEmailSpool();

        $emailSpool->setStatus(EmailSpoolInterface::STATUS_IN_PROGRESS);
        $this->em->flush($emailSpool);
    }
}