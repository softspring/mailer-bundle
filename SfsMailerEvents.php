<?php

namespace Softspring\MailerBundle;

class SfsMailerEvents
{
    /**
     * @Event("Softspring\MailerBundle\Event\EmailSpoolEvent")
     */
    const EMAIL_SPOOL_QUEUED = 'sfs_mailer.email_spool.queued';

    /**
     * @Event("Softspring\MailerBundle\Event\EmailSpoolEvent")
     */
    const EMAIL_SPOOL_SENDING = 'sfs_mailer.email_spool.sending';

    /**
     * @Event("Softspring\MailerBundle\Event\EmailSpoolEvent")
     */
    const EMAIL_SPOOL_SENT = 'sfs_mailer.email_spool.sent';

    /**
     * @Event("Softspring\MailerBundle\Event\EmailSpoolEvent")
     */
    const EMAIL_SPOOL_FAILED = 'sfs_mailer.email_spool.failed';
}