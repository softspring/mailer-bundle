<?php

namespace Softspring\MailerBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Event\MessageEvent;
use Symfony\Component\Mime\Email;

class DeliverMailsToListener implements EventSubscriberInterface
{
    /**
     * @var string[]
     */
    protected $to;

    /**
     * DeliverMailsToListener constructor.
     *
     * @param string[] $to
     */
    public function __construct(array $to)
    {
        $this->to = $to;
    }

    public static function getSubscribedEvents()
    {
        return [
            MessageEvent::class => ['onMessageEvent'],
        ];
    }

    public function onMessageEvent(MessageEvent $event)
    {
        $email = $event->getMessage();

        if ($email instanceof Email) {
            call_user_func_array([$email, 'to'], $this->to);
        }
    }
}
