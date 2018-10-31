<?php

namespace Softspring\MailerBundle\Mailer;

use Softspring\MailerBundle\Model\Template;
use Swift_Message;

class TemplateMessage extends Swift_Message
{
    /**
     * @var Template|null
     */
    protected $template;

    /**
     * @return null|Template
     */
    public function getTemplate(): ?Template
    {
        return $this->template;
    }

    /**
     * @param null|Template $template
     *
     * @return TemplateMessage
     */
    public function setTemplate(?Template $template): TemplateMessage
    {
        $this->template = $template;

        return $this;
    }
}