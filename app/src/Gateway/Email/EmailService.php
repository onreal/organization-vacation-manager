<?php

namespace Up\Gateway\Email;

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
     * @param IMailer $mailer
     */
    public function __construct(IMailer $mailer)
    {
        $this->mailer = $mailer->getMailer();
    }

    /**
     * @param string $email
     * @param string $subject
     * @param string $body
     * @throws Exception
     */
    public function send(string $email, string $subject, string $body)
    {
        $this->mailer->addAddress($email);
        $this->mailer->Subject = $subject;
        $this->mailer->MsgHTML($body);
        if (!$this->mailer->send()) {
            throw new Exception('Email could not send => ' . $this->mailer->ErrorInfo);
        }
    }
}
