<?php

namespace Softspring\MailerBundle\Controller\Admin;

use Softspring\Component\MimeTranslatable\ExampleEmailInterface;
use Softspring\MailerBundle\Form\Admin\SendTestForm;
use Softspring\MailerBundle\Mime\TranslatableBodyRenderer;
use Softspring\MailerBundle\Template\TemplateLoader;
use Softspring\UserBundle\Model\NameSurnameInterface;
use Softspring\UserBundle\Model\UserWithEmailInterface;
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
    protected TemplateLoader $templateLoader;
    protected TranslatorInterface $translator;
    protected MailerInterface $mailer;
    protected TranslatableBodyRenderer $renderer;
    protected array $locales;

    public function __construct(TemplateLoader $templateLoader, TranslatorInterface $translator, MailerInterface $mailer, TranslatableBodyRenderer $renderer, array $locales)
    {
        $this->templateLoader = $templateLoader;
        $this->translator = $translator;
        $this->mailer = $mailer;
        $this->renderer = $renderer;
        $this->locales = $locales;
    }

    public function search(): Response
    {
        $templates = $this->templateLoader->getTemplateCollection()->getTemplates();

        return $this->render('@SfsMailer/admin/mailer_template/search.html.twig', [
            'templates' => $templates,
        ]);
    }

    public function test(string $template, Request $request): Response
    {
        $template = $this->templateLoader->getTemplateCollection()->getTemplate($template);

        if (!$template) {
            return $this->redirectToRoute('sfs_mailer_history_search');
        }

        /** @var NameSurnameInterface|UserWithEmailInterface $user */
        $user = $this->getUser();

        $data = [
            'locale' => $request->getLocale(),
            'toName' => $user instanceof NameSurnameInterface ? $user->getName() : '',
            'toEmail' => $user instanceof UserWithEmailInterface ? $user->getEmail() : '',
        ];
        $form = $this->createForm(SendTestForm::class, $data, [
            'locales' => $this->locales,
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            ['toEmail' => $toEmail, 'toName' => $toName, 'locale' => $locale] = $formData;

            try {
                /** @var ExampleEmailInterface|string $mailClass */
                $mailClass = $template->getClass();

                if (!(new \ReflectionClass($mailClass))->implementsInterface(ExampleEmailInterface::class)) {
                    throw new \RuntimeException(sprintf('%s mail class does not implements %s', $mailClass, ExampleEmailInterface::class));
                }

                $mail = $mailClass::generateExample($this->translator, $locale)
                    ->to(new Address($toEmail, $toName))
                ;

                $this->mailer->send($mail);
            } catch (LoaderError $e) {
                $form->addError(new FormError('Template is missing'));
            }

            return $this->redirectToRoute('sfs_mailer_templates_preview', ['template' => $template]);
        }

        return $this->render('@SfsMailer/admin/mailer_template/test.html.twig', [
            'template' => $template,
            'form' => $form->createView(),
        ]);
    }

    public function preview(string $template, Request $request): Response
    {
        $template = $this->templateLoader->getTemplateCollection()->getTemplate($template);

        if (!$template) {
            // not found
            return $this->redirectToRoute('sfs_mailer_history_search');
        }

        /** @var ExampleEmailInterface|string $mailClass */
        $mailClass = $template->getClass();

        if (!(new \ReflectionClass($mailClass))->implementsInterface(ExampleEmailInterface::class)) {
            throw new \RuntimeException(sprintf('%s mail class does not implements %s', $mailClass, ExampleEmailInterface::class));
        }

        $mail = $mailClass::generateExample($this->translator, $locale = $request->get('locale', $request->getLocale()));
        $this->renderer->render($mail);

        return $this->render('@SfsMailer/admin/mailer_template/preview.html.twig', [
            'template' => $template,
            'mail' => $mail,
            'locales' => $this->locales,
            'preview_locale' => $locale,
        ]);
    }
}
