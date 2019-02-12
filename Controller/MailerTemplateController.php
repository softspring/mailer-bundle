<?php

namespace Softspring\MailerBundle\Controller;

use Softspring\MailerBundle\Model\Template;
use Softspring\MailerBundle\Model\TemplateCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Response;

class MailerTemplateController extends AbstractController
{
    public function search(): Response
    {
        $templates = $this->getCollection()->getTemplates();

        return $this->render('@SfsMailer/mailer_template/search.html.twig', [
            'templates' => $templates,
        ]);
    }

    public function test(string $templateId): Response
    {
        $template = $this->getTemplate($templateId);

        if (!$template) {
            return $this->redirectToRoute('sfs_mailer_history_search');
        }

        $form = $this->createTestForm($template);

        if ($form->isSubmitted() && $form->isValid()) {

        }

        return $this->render('@SfsMailer/mailer_template/test.html.twig', [
            'template' => $template,
            'form' => $form->createView(),
        ]);
    }


    protected function createTestForm(Template $template)
    {
        $data = [
            'toEmail' => '',
            'toName' => '',
            'fromEmail' => $template->getFromEmail(),
            'fromName' => $template->getFromName(),
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

    protected function getTemplate(string $templateId): ?Template
    {
        return $this->getCollection()->getTemplate($templateId);
    }

    protected function getCollection(): TemplateCollection
    {
        return $this->get('sfs_mailer')->getTemplateCollection();
    }
}