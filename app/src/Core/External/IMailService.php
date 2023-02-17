<?php

namespace Up\Core\External;

interface IMailService
{
    public function send(string $email, string $subject, string $body);
}
