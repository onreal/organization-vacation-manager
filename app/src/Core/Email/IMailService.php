<?php

namespace Up\Core\Email;

interface IMailService
{
    public function send(string $email, string $subject, string $body);
}
