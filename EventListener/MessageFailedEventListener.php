<?php

namespace Softspring\MailerBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Softspring\MailerBundle\Event\EmailSpoolEvent;
use Softspring\MailerBundle\Model\EmailSpoolInterface;
use Softspring\MailerBundle\SfsMailerEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MessageFailedEventListener implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var bool
     */
    protected $removeFailed;

    /**
     * MessageFailedEventListener constructor.
     *
     * @param EntityManagerInterface $em
     * @param bool                   $removeFailed
     */
    public function __construct(EntityManagerInterface $em, bool $removeFailed)
    {
        $this->em = $em;
        $this->removeFailed = $removeFailed;
    }

    public static function getSubscribedEvents()
    {
        return [
            SfsMailerEvents::EMAIL_SPOOL_SENT => [['onEmailSpoolFailed']]
        ];
    }

    public function onEmailSpoolFailed(EmailSpoolEvent $event)
    {
        $emailSpool = $event->getEmailSpool();

        if ($this->removeFailed) {
            $this->em->remove($emailSpool);
            $this->em->flush($emailSpool);
        } else {
            $emailSpool->setStatus(EmailSpoolInterface::STATUS_FAILED);
            $this->em->flush($emailSpool);
        }
    }
}