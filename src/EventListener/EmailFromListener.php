<?php

namespace Softspring\MailerBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Event\MessageEvent;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class EmailFromListener implements EventSubscriberInterface
{
    protected string $fromAddress;

    protected ?string $fromName;

    public function __construct(string $fromAddress, ?string $fromName)
    {
        $this->fromAddress = $fromAddress;
        $this->fromName = $fromName;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            MessageEvent::class => ['onMessageEvent'],
        ];
    }

    public function onMessageEvent(MessageEvent $event): void
    {
        $email = $event->getMessage();

        if ($email instanceof Email) {
            $email->from(new Address($this->fromAddress, $this->fromName));
        }
    }
}
