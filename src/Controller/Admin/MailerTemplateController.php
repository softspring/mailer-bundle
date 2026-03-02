<?php

namespace Softspring\MailerBundle\Controller\Admin;

use ReflectionClass;
use ReflectionException;
use RuntimeException;
use Softspring\Component\MimeTranslatable\ExampleEmailInterface;
use Softspring\MailerBundle\Form\Admin\SendTestForm;
use Softspring\MailerBundle\Mime\TranslatableBodyRenderer;
use Softspring\MailerBundle\Template\Template;
use Softspring\MailerBundle\Template\TemplateLoader;
use Softspring\UserBundle\Model\NameSurnameInterface;
use Softspring\UserBundle\Model\UserWithEmailInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\User\UserInterface;
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

    /**
     * @throws ReflectionException
     * @throws TransportExceptionInterface
     */
    public function test(string $template, Request $request): Response
    {
        $template = $this->templateLoader->getTemplateCollection()->getTemplate($template);

        if (!$template instanceof Template) {
            return $this->redirectToRoute('sfs_mailer_history_search');
        }

        /** @var UserInterface&(NameSurnameInterface|UserWithEmailInterface) $user */
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
                $mailClass = $template->getClass();

                if (!new ReflectionClass($mailClass)->implementsInterface(ExampleEmailInterface::class)) {
                    throw new RuntimeException(sprintf('%s mail class does not implements %s', $mailClass, ExampleEmailInterface::class));
                }

                /** @var ExampleEmailInterface $mailClass */
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
            'form' => $form,
        ]);
    }

    /**
     * @throws ReflectionException
     */
    public function preview(string $template, Request $request): Response
    {
        $template = $this->templateLoader->getTemplateCollection()->getTemplate($template);

        if (!$template instanceof Template) {
            // not found
            return $this->redirectToRoute('sfs_mailer_history_search');
        }

        $mailClass = $template->getClass();

        if (!new ReflectionClass($mailClass)->implementsInterface(ExampleEmailInterface::class)) {
            throw new RuntimeException(sprintf('%s mail class does not implements %s', $mailClass, ExampleEmailInterface::class));
        }

        /** @var ExampleEmailInterface $mailClass */
        $mail = $mailClass::generateExample($this->translator, $locale = $request->query->get('locale', $request->getLocale()));
        $this->renderer->render($mail);

        return $this->render('@SfsMailer/admin/mailer_template/preview.html.twig', [
            'template' => $template,
            'mail' => $mail,
            'locales' => $this->locales,
            'preview_locale' => $locale,
        ]);
    }
}
