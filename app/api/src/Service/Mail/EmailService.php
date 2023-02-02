<?php

namespace Up\Service\Mail;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Up\Core\Email\IMailService;

class EmailService implements IMailService
{
    /**
     * @var PHPMailer
     */
    private PHPMailer $mailer;

    /**
     * @param IMail $mailer
     */
    public function __construct(IMail $mailer)
    {
        $this->mailer = $mailer->getMailer();
    }

    /**
     * @param string $email
     * @param string $subject
     * @param string $body
     * @throws Exception PHPMailer\Exception
     */
    public function send(string $email, string $subject, string $body)
    {
        $this->mailer->addAddress($email);
        $this->mailer->Subject = $subject;
        $this->mailer->Body = $body;
        $this->mailer->send();
    }
}
