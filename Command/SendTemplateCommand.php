<?php

namespace Softspring\MailerBundle\Command;

use Softspring\MailerBundle\Mailer\TemplateMailer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SendTemplateCommand extends ContainerAwareCommand
{
    /**
     * @var TemplateMailer
     */
    protected $templateMailer;

    /**
     * DebugTemplatesCommand constructor.
     *
     * @param TemplateMailer $templateMailer
     */
    public function __construct(TemplateMailer $templateMailer)
    {
        $this->templateMailer = $templateMailer;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('sfs_mailer:template:send');
        $this->addArgument('template-id', InputArgument::REQUIRED);
        $this->addArgument('to-email', InputArgument::REQUIRED);
        $this->addArgument('to-name', InputArgument::OPTIONAL);
        $this->addOption('locale', null, InputOption::VALUE_OPTIONAL, '', 'es');
        $this->addOption('from-email', null, InputOption::VALUE_OPTIONAL);
        $this->addOption('from-name', null, InputOption::VALUE_OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $templateId = $input->getArgument('template-id');
        $locale = $input->getOption('locale');
        $toEmail = $input->getArgument('to-email');
        $toName = $input->getArgument('to-name');
        $fromEmail = $input->getOption('from-email');
        $fromName = $input->getOption('from-name');

        $this->templateMailer->send($templateId, $locale, null, $toEmail, $toName, $fromEmail, $fromName);
    }
}