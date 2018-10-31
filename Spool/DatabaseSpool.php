<?php

namespace Softspring\MailerBundle\Spool;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Softspring\MailerBundle\Event\EmailSpoolEvent;
use Softspring\MailerBundle\Mailer\TemplateMessage;
use Softspring\MailerBundle\Model\EmailSpoolInterface;
use Softspring\MailerBundle\SfsMailerEvents;
use Swift_ConfigurableSpool;
use Swift_Mime_SimpleMessage;
use Swift_Transport;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DatabaseSpool extends Swift_ConfigurableSpool
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * DatabaseSpool constructor.
     *
     * @param EntityManagerInterface   $em
     * @param string                   $class
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EntityManagerInterface $em, string $class, EventDispatcherInterface $dispatcher)
    {
        $this->em = $em;
        $this->class = $class;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @inheritdoc
     */
    public function start()
    {
    }

    /**
     * @inheritdoc
     */
    public function stop()
    {
    }

    /**
     * @inheritdoc
     */
    public function isStarted()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function queueMessage(Swift_Mime_SimpleMessage $message)
    {
        /** @var EmailSpoolInterface $emailSpool */
        $emailSpool = new $this->class;
        $emailSpool->setStatus(EmailSpoolInterface::STATUS_PENDING);
        $emailSpool->setMessage($message);

        if ($message instanceof TemplateMessage) {
            $emailSpool->setTemplateId($message->getTemplate()->getId());
        }

        $this->em->persist($emailSpool);
        $this->em->flush($emailSpool);

        $this->dispatcher->dispatch(SfsMailerEvents::EMAIL_SPOOL_QUEUED, new EmailSpoolEvent($emailSpool));

        return true;
    }

    /**
     * @inheritdoc
     */
    public function flushQueue(Swift_Transport $transport, &$failedRecipients = null)
    {
        if (!$transport->isStarted()) {
            $transport->start();
        }

        $emailSpools = $this->getPendingMessages($this->getMessageLimit() ?? 0);

        if (empty($emailSpools)) {
            return 0;
        }

        $failedRecipients = (array) $failedRecipients;
        $count = 0;
        $time = time();
        foreach ($emailSpools as $emailSpool) {
            $this->dispatcher->dispatch(SfsMailerEvents::EMAIL_SPOOL_SENDING, new EmailSpoolEvent($emailSpool));

            $message = $emailSpool->getMessage();

            $count += $transport->send($message, $failedRecipients);

            $this->dispatcher->dispatch(SfsMailerEvents::EMAIL_SPOOL_SENT, new EmailSpoolEvent($emailSpool));
            // $this->manager->messageSent($emailSpool);

            if ($this->getTimeLimit() && (time() - $time) >= $this->getTimeLimit()) {
                break;
            }
        }

        return $count;
    }

    /**
     * @inheritdoc
     */
    public function getPendingMessages(int $limit = 0): array
    {
        $collection = $this->getRepository()->findBy([
            'status' => EmailSpoolInterface::STATUS_PENDING,
        ], [], $limit ?? null);

        return $collection;
    }

    /**
     * @return ObjectRepository
     */
    protected function getRepository(): ObjectRepository
    {
        return $this->em->getRepository($this->class);
    }
}