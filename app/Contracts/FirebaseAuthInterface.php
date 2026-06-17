<?php

namespace App\Contracts;

interface FirebaseAuthInterface
{
    public function getAccessToken(): string;

    public function isTokenValid(): bool;
}
