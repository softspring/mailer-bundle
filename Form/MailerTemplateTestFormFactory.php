<?php

namespace Softspring\MailerBundle\Form;

use Softspring\MailerBundle\Template\Template;
use Softspring\UserBundle\Model\NameSurnameInterface;
use Softspring\UserBundle\Model\UserInterface;
use Softspring\UserBundle\Model\UserWithEmailInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MailerTemplateTestFormFactory
{
    /**
     * @var array
     */
    protected $locales;

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * MailerTemplateTestFormFactory constructor.
     *
     * @param array                 $locales
     * @param TokenStorageInterface $tokenStorage
     * @param RequestStack          $requestStack
     * @param FormFactoryInterface  $formFactory
     */
    public function __construct(array $locales, TokenStorageInterface $tokenStorage, RequestStack $requestStack, FormFactoryInterface $formFactory)
    {
        $this->locales = $locales;
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;
        $this->formFactory = $formFactory;
    }

    /**
     * @param Template $template
     *
     * @return FormInterface
     */
    public function createTestForm(Template $template): FormInterface
    {
        $formBuilder = $this->createFormBuilder();

        $user = $this->getUser();

        $data = [
            'toEmail' => $user->getEmail(),
            'toName' => $user instanceof NameSurnameInterface ? $user->getName() : '',
            'fromEmail' => 'development@softspring.eu',//$template->getFromEmail() ?? '',
            'fromName' => 'Softspring DEV',//$template->getFromName() ?? '',
            'locale' => $this->requestStack->getCurrentRequest()->getLocale(),
        ];

        $locales = array_merge($this->locales, [$this->requestStack->getCurrentRequest()->getLocale()]);
        $formBuilder->add('locale', ChoiceType::class, [
            'choices' => array_combine($locales, $locales),
        ]);

        $formBuilder->add('toEmail', EmailType::class);
        $formBuilder->add('toName', TextType::class);

        $example = $template->getExample();
        $formBuilder->add('emailFields', $example->getFormType());
        $data['emailFields'] = $example->getEmptyData();

        $formBuilder->setData($data);

        return $formBuilder->getForm();
    }

    /**
     * @return UserInterface|UserWithEmailInterface
     */
    protected function getUser(): UserInterface
    {
        return $this->tokenStorage->getToken()->getUser();
    }

    protected function createFormBuilder($data = null, array $options = []): FormBuilderInterface
    {
        return $this->formFactory->createBuilder(FormType::class, $data, $options);
    }
}