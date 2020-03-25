<?php

namespace Softspring\MailerBundle\Controller\Admin;

use Softspring\MailerBundle\Model\Template;
use Softspring\MailerBundle\Template\TemplateLoader;
use Softspring\UserBundle\Model\UserInterface;
use Softspring\UserBundle\Model\UserWithEmailInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Error\LoaderError;

class MailerTemplateController extends AbstractController
{
//    /**
//     * @var string|null
//     */
//    protected $senderName;
//
//    /**
//     * @var string|null
//     */
//    protected $senderEmail;

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
     * MailerTemplateController constructor.
     *
     * @param TemplateLoader      $templateLoader
     * @param TranslatorInterface $translator
     * @param MailerInterface     $mailer
     */
    public function __construct(TemplateLoader $templateLoader, TranslatorInterface $translator, MailerInterface $mailer)
    {
        $this->templateLoader = $templateLoader;
        $this->translator = $translator;
        $this->mailer = $mailer;
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

        $form = $this->createTestForm($template)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            ['toEmail' => $toEmail, 'toName' => $toName, 'fromEmail' => $fromEmail, 'fromName' => $fromName] = $formData;
            $context = array_slice($formData, 4);

            try {
                $email = $template->getExample()->getEmail($context['emailFields'], $this->translator, 'es');
                $this->mailer->send($email);
            } catch (LoaderError $e) {
                $form->addError(new FormError('Template is missing'));
            }

            // $this->templateLoader->send($templateId, 'es', $context, $toEmail, $toName, $fromEmail, $fromName);

            // return $this->redirectToRoute('sfs_mailer_templates_search');
        }

        return $this->render('@SfsMailer/admin/mailer_template/test.html.twig', [
            'template' => $template,
            'form' => $form->createView(),
        ]);
    }


    protected function createTestForm(Template $template)
    {
        /** @var UserInterface|UserWithEmailInterface $user */
        $user = $this->getUser();

        $data = [
            'toEmail' => $user->getEmail(),
            'toName' => method_exists($user, 'getName') ? $user->getName() : '',
            'fromEmail' => '',//$template->getFromEmail() ?? '',
            'fromName' => '',//$template->getFromName() ?? '',
        ];

        $formBuilder = $this->createFormBuilder();

        $formBuilder->add('toEmail', EmailType::class);
        $formBuilder->add('toName', TextType::class);
        $formBuilder->add('fromEmail', EmailType::class, [
            'disabled' => true,
        ]);
        $formBuilder->add('fromName', TextType::class, [
            'disabled' => true,
        ]);

        $example = $template->getExample();
        $formBuilder->add('emailFields', $example->getFormType());
        $data['emailFields'] = $example->getEmptyData();

        $formBuilder->setData($data);

        return $formBuilder->getForm();
    }
}