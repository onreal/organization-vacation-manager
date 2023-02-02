<?php

namespace Up\Service\Mail;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class MailPHPMailer implements IMail
{

    /**
     * Instantiate mailer for application use, here we can set up also SMTP credentials
     * @throws Exception
     */
    public function getMailer(): PHPMailer
    {
        $mailer = new PHPMailer();
        $mailer->setFrom($_ENV['MAIL_FROM_EMAIL'], $_ENV['MAIL_FROM_NAME']);
        $mailer->isHTML(true);

        return $mailer;
    }
}
