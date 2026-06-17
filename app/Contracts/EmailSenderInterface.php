<?php

namespace App\Contracts;

interface EmailSenderInterface
{
    public function send(object $recipient, object $mail): bool;

    public function sendSync(object $recipient, object $mail): bool;
}
