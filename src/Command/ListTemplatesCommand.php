<?php

namespace Softspring\MailerBundle\Command;

use Softspring\MailerBundle\Template\Template;
use Softspring\MailerBundle\Template\TemplateLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListTemplatesCommand extends Command
{
    protected TemplateLoader $templateLoader;

    public function __construct(TemplateLoader $templateLoader)
    {
        $this->templateLoader = $templateLoader;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('sfs_mailer:template:list');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var Template $template */
        foreach ($this->templateLoader->getTemplateCollection()->getTemplates() as $key => $template) {
            $output->writeln(" - $key : {$template->getId()}");
        }

        return Command::SUCCESS;
    }
}
