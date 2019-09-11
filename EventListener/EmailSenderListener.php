<?php

namespace Softspring\MailerBundle\EventListener;

use Psr\Log\LoggerInterface;
use Softspring\MailerBundle\Event\EmailSpoolEvent;
use Softspring\MailerBundle\SfsMailerEvents;
use Softspring\MailerBundle\Spool\DatabaseSpool;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class EmailSenderListener implements EventSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * EmailSenderListener constructor.
     * @param ContainerInterface $container
     * @param LoggerInterface $logger
     */
    public function __construct(ContainerInterface $container, LoggerInterface $logger)
    {
        $this->container = $container;
        $this->logger = $logger;
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

    public function onSpoolQueued(EmailSpoolEvent $event)
    {
        $this->somethingToSend = true;
    }

    public function onTerminateSendEmails(TerminateEvent $event)
    {
        if (!$this->somethingToSend) {
            return;
        }

        foreach ($this->getMailers() as $name) {
            if (!$this->supportMailer($name)) {
                continue;
            }

            $this->applySpoolSend($name);
        }
    }

    protected function getMailers(): array
    {
        return array_keys($this->container->getParameter('swiftmailer.mailers'));
    }

    protected function supportMailer(string $name): bool
    {
//        if (!method_exists($this->container, 'initialized') ? $this->container->initialized(sprintf('swiftmailer.mailer.%s', $name)) : true) {
//            return false;
//        }

        if (!$this->container->getParameter(sprintf('swiftmailer.mailer.%s.spool.enabled', $name))) {
            return false;
        }

        return true;
    }

    protected function getMailerSpool(string $name): ?DatabaseSpool
    {
        $mailer = $this->container->get(sprintf('swiftmailer.mailer.%s', $name));

        $transport = $mailer->getTransport();
        if (!$transport instanceof \Swift_Transport_SpoolTransport) {
            return null;
        }

        $spool = $transport->getSpool();
        if (!$spool instanceof DatabaseSpool) {
            return null;
        }

        return $spool;
    }

    protected function applySpoolSend(string $name): void
    {
        $spool = $this->getMailerSpool($name);

        if (!$spool instanceof DatabaseSpool) {
            return;
        }

        try {
            /** @var \Swift_Transport_SpoolTransport $transport */
            $transport = $this->container->get(sprintf('swiftmailer.mailer.%s.transport.real', $name));
            $spool->flushQueue($transport);
        } catch (\Swift_TransportException $exception) {
            if (null !== $this->logger) {
                $this->logger->error(sprintf('Exception occurred while flushing email queue: %s', $exception->getMessage()));
            }
        }
    }
}