<?php

namespace Softspring\MailerBundle\Controller\Admin;

use Softspring\MailerBundle\Mailer\TemplateMailer;
use Softspring\MailerBundle\Model\Template;
use Softspring\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MailerTemplateController extends AbstractController
{
    /**
     * @var string|null
     */
    protected $senderName;

    /**
     * @var string|null
     */
    protected $senderEmail;

    /**
     * @var TemplateMailer
     */
    protected $templateMailer;

    /**
     * MailerTemplateController constructor.
     *
     * @param string|null    $senderName
     * @param string|null    $senderEmail
     * @param TemplateMailer $templateMailer
     */
    public function __construct(?string $senderName, ?string $senderEmail, TemplateMailer $templateMailer)
    {
        $this->senderName = $senderName;
        $this->senderEmail = $senderEmail;
        $this->templateMailer = $templateMailer;
    }

    public function search(): Response
    {
        $templates = $this->templateMailer->getTemplateCollection()->getTemplates();

        return $this->render('@SfsMailer/admin/mailer_template/search.html.twig', [
            'templates' => $templates,
        ]);
    }

    public function test(string $templateId, Request $request): Response
    {
        $template = $this->templateMailer->getTemplateCollection()->getTemplate($templateId);

        if (!$template) {
            return $this->redirectToRoute('sfs_mailer_history_search');
        }

        $form = $this->createTestForm($template)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            ['toEmail' => $toEmail, 'toName' => $toName, 'fromEmail' => $fromEmail, 'fromName' => $fromName] = $formData;
            $context = array_slice($formData, 4);
            $this->templateMailer->send($templateId, 'es', $context, $toEmail, $toName, $fromEmail, $fromName);

            return $this->redirectToRoute('sfs_mailer_templates_search');
        }

        return $this->render('@SfsMailer/admin/mailer_template/test.html.twig', [
            'template' => $template,
            'form' => $form->createView(),
        ]);
    }


    protected function createTestForm(Template $template)
    {
        /** @var UserInterface $user */
        $user = $this->getUser();

        $data = [
            'toEmail' => $user->getEmail(),
            'toName' => method_exists($user, 'getName') ? $user->getName() : '',
            'fromEmail' => $template->getFromEmail() ?? $this->senderEmail,
            'fromName' => $template->getFromName() ?? $this->senderName,
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

        foreach ($template->getExampleContext() as $key => $value) {
            $this->addExampleField($formBuilder, $key, $value, $data);
        }

        $formBuilder->setData($data);

        return $formBuilder->getForm();
    }

    protected function addExampleField(FormBuilderInterface $formBuilder, string $key, $value, array &$data)
    {
        $key = str_replace('.', '_', "$key");

        if (is_array($value)) {
            foreach ($value as $key2 => $value2) {
                $this->addExampleField($formBuilder, "$key.$key2", $value2, $data);
            }
        } else {
            $formBuilder->add($key, TextType::class);
            $data[$key] = $value;
        }
    }
}