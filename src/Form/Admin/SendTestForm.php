<?php

namespace Softspring\MailerBundle\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SendTestForm extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'sfs_mailer',
            'label_format' => 'admin_templates.send_test.form.%name%.label',
            'locales' => ['en', 'es'],
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('locale', ChoiceType::class, [
            'choices' => array_combine($options['locales'], $options['locales']),
        ]);

        $builder->add('toEmail', EmailType::class);
        $builder->add('toName', TextType::class);
    }
}
