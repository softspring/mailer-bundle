<?php

namespace Softspring\MailerBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Softspring\MailerBundle\Model\EmailSpoolInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MailerHistoryController extends AbstractController
{
    public function search(Request $request): Response
    {
        $mails = $this->getRepository()->findBy([]);

        return $this->render('@SfsMailer/mailer_history/search.html.twig', [
            'mails' => $mails,
        ]);
    }

    public function details($messageId, Request $request): Response
    {
        $mail = $this->getRepository()->findOneById($messageId);

        return $this->render('@SfsMailer/mailer_history/details.html.twig', [
            'mail' => $mail,
        ]);
    }

    protected function getRepository(): EntityRepository
    {
        return $this->getDoctrine()->getRepository(EmailSpoolInterface::class);
    }
}