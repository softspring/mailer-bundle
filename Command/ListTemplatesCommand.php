<?php

namespace Softspring\MailerBundle\Command;

use Softspring\MailerBundle\Model\Template;
use Softspring\MailerBundle\Template\TemplateLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListTemplatesCommand extends Command
{
    /**
     * @var TemplateLoader
     */
    protected $templateLoader;

    /**
     * DebugTemplatesCommand constructor.
     *
     * @param TemplateLoader $templateLoader
     */
    public function __construct(TemplateLoader $templateLoader)
    {
        $this->templateLoader = $templateLoader;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('sfs_mailer:template:list');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Template $template */
        foreach ($this->templateLoader->getTemplateCollection()->getTemplates() as $key => $template) {
            $output->writeln(" - $key : {$template->getName()}");
        }

        return 0;
    }
}