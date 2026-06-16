<?php

namespace App\Contracts;

interface WordToPdfInterface
{
    public function convertToPdf($wordFile, string $title = 'document'): ?string;

    public function hasWordSupport(): bool;
}
