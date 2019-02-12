<?php

namespace Softspring\MailerBundle\Command;

use Softspring\MailerBundle\Mailer\TemplateMailer;
use Softspring\MailerBundle\Model\Template;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListTemplatesCommand extends Command
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
        $this->setName('sfs_mailer:template:list');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Template $template */
        foreach ($this->templateMailer->getTemplateCollection()->getTemplates() as $key => $template) {
            $output->writeln(" - $key : {$template->getTwigTemplate()}");
        }
    }
}