<?php

namespace Softspring\MailerBundle\Controller\Admin;

use Doctrine\ORM\EntityRepository;
use Softspring\MailerBundle\Model\EmailHistoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MailerHistoryController extends AbstractController
{
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
     * @return EntityRepository
     */
    protected function getRepository(): EntityRepository
    {
        return $this->getDoctrine()->getRepository(EmailHistoryInterface::class);
    }
}