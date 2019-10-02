<?php

namespace Softspring\MailerBundle\Controller\Admin;

use Doctrine\ORM\EntityRepository;
use Softspring\MailerBundle\Model\EmailSpoolInterface;
use Softspring\MailerBundle\Spool\SpoolManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MailerHistoryController extends AbstractController
{
    /**
     * @var SpoolManager
     */
    protected $spoolManager;

    /**
     * MailerHistoryController constructor.
     *
     * @param SpoolManager|null $spoolManager
     */
    public function __construct(?SpoolManager $spoolManager)
    {
        if (!$spoolManager instanceof SpoolManager) {
            throw new InvalidConfigurationException('MailerHistoryController requires sfs_mailer spooling to work');
        }

        $this->spoolManager = $spoolManager;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function search(Request $request): Response
    {
        $mails = $this->getRepository()->findBy([], ['createdAt' => 'DESC']);

        return $this->render('@SfsMailer/admin/mailer_history/search.html.twig', [
            'mails' => $mails,
        ]);
    }

    /**
     * @param         $messageId
     * @param Request $request
     *
     * @return Response
     */
    public function details($messageId, Request $request): Response
    {
        $mail = $this->getRepository()->findOneById($messageId);

        return $this->render('@SfsMailer/admin/mailer_history/details.html.twig', [
            'mail' => $mail,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function sendSpool(Request $request): Response
    {
        $this->spoolManager->send();

        return $this->redirectToRoute('sfs_mailer_history_search');
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository(): EntityRepository
    {
        return $this->getDoctrine()->getRepository(EmailSpoolInterface::class);
    }
}