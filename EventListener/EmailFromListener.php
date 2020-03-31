<?php

namespace Softspring\MailerBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Event\MessageEvent;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class EmailFromListener implements EventSubscriberInterface
{
    /**
     * @var string
     */
    protected $fromAddress;

    /**
     * @var string|null
     */
    protected $fromName;

    /**
     * EmailFromListener constructor.
     *
     * @param string      $fromAddress
     * @param string|null $fromName
     */
    public function __construct(string $fromAddress, ?string $fromName)
    {
        $this->fromAddress = $fromAddress;
        $this->fromName = $fromName;
    }

    public static function getSubscribedEvents()
    {
        return [
            MessageEvent::class => ['onMessageEvent']
        ];
    }

    public function onMessageEvent(MessageEvent $event)
    {
        $email = $event->getMessage();

        if ($email instanceof Email) {
            $email->from(new Address($this->fromAddress, $this->fromName));
        }
    }
}