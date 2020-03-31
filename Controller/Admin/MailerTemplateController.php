<?php

namespace Softspring\MailerBundle\Controller\Admin;

use Softspring\MailerBundle\Form\MailerTemplateTestFormFactory;
use Softspring\MailerBundle\Template\TemplateLoader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Error\LoaderError;

class MailerTemplateController extends AbstractController
{
    /**
     * @var TemplateLoader
     */
    protected $templateLoader;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var MailerInterface
     */
    protected $mailer;

    /**
     * @var MailerTemplateTestFormFactory
     */
    protected $templateTestFormFactory;

    /**
     * MailerTemplateController constructor.
     *
     * @param TemplateLoader                $templateLoader
     * @param TranslatorInterface           $translator
     * @param MailerInterface               $mailer
     * @param MailerTemplateTestFormFactory $templateTestFormFactory
     */
    public function __construct(TemplateLoader $templateLoader, TranslatorInterface $translator, MailerInterface $mailer, MailerTemplateTestFormFactory $templateTestFormFactory)
    {
        $this->templateLoader = $templateLoader;
        $this->translator = $translator;
        $this->mailer = $mailer;
        $this->templateTestFormFactory = $templateTestFormFactory;
    }

    public function search(): Response
    {
        $templates = $this->templateLoader->getTemplateCollection()->getTemplates();

        return $this->render('@SfsMailer/admin/mailer_template/search.html.twig', [
            'templates' => $templates,
        ]);
    }

    public function test(string $templateId, Request $request): Response
    {
        $template = $this->templateLoader->getTemplateCollection()->getTemplate($templateId);

        if (!$template) {
            return $this->redirectToRoute('sfs_mailer_history_search');
        }

        $form = $this->templateTestFormFactory->createTestForm($template)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            ['toEmail' => $toEmail, 'toName' => $toName, 'locale' => $locale] = $formData;

            try {
                $email = $template->getExample()->getEmail($formData['emailFields'], $this->translator, $locale)
                    ->to(new Address($toEmail, $toName))
                ;

                $this->mailer->send($email);
            } catch (LoaderError $e) {
                $form->addError(new FormError('Template is missing'));
            }

            return $this->redirectToRoute('sfs_mailer_templates_search');
        }

        return $this->render('@SfsMailer/admin/mailer_template/test.html.twig', [
            'template' => $template,
            'form' => $form->createView(),
        ]);
    }
}