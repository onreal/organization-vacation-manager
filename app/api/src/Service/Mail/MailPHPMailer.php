<?php

namespace Up\Service\Mail;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class MailPHPMailer implements IMail
{

    /**
     * Instantiate mailer for application use, here we can set up also SMTP credentials
     * @throws Exception
     */
    public function getMailer(): PHPMailer
    {
        $mailer = new PHPMailer();
        if ($_ENV['IS_SMTP_USED']) {
            $mailer->isSMTP();
            $mailer->SMTPDebug = SMTP::DEBUG_SERVER;
            $mailer->Host       = $_ENV['SMTP_HOST'];
            $mailer->FromName   = $_ENV['MAIL_FROM_NAME'];
            $mailer->SMTPAuth   = true;
            $mailer->Username   = $_ENV['SMTP_USER'];
            $mailer->Password   = $_ENV['SMPT_PASSWORD'];
            $mailer->SMTPSecure = $_ENV['SMTP_ENCRYPTION'];
            $mailer->Port       = $_ENV['SMTP_PORT'];
        } else {
            $mailer->isSendmail();
        }
        $mailer->setFrom($_ENV['MAIL_FROM_EMAIL'], $_ENV['MAIL_FROM_NAME']);
        $mailer->isHTML(true);

        return $mailer;
    }
}
