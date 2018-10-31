<?php

namespace Softspring\MailerBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Softspring\MailerBundle\Event\EmailSpoolEvent;
use Softspring\MailerBundle\Model\EmailSpoolInterface;
use Softspring\MailerBundle\SfsMailerEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MessageSentEventListener implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var bool
     */
    protected $removeSent;

    /**
     * MessageSentEventListener constructor.
     *
     * @param EntityManagerInterface $em
     * @param bool                   $removeSent
     */
    public function __construct(EntityManagerInterface $em, bool $removeSent)
    {
        $this->em = $em;
        $this->removeSent = $removeSent;
    }

    public static function getSubscribedEvents()
    {
        return [
            SfsMailerEvents::EMAIL_SPOOL_SENT => [['onEmailSpoolSent']]
        ];
    }

    public function onEmailSpoolSent(EmailSpoolEvent $event)
    {
        $emailSpool = $event->getEmailSpool();

        if ($this->removeSent) {
            $this->em->remove($emailSpool);
            $this->em->flush($emailSpool);
        } else {
            $emailSpool->setStatus(EmailSpoolInterface::STATUS_SENT);
            $this->em->flush($emailSpool);
        }
    }
}