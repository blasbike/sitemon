<?php

declare(strict_types=1);

namespace Sitemon\Interfaces;

interface FileWriterInterface
{
    public function writeFile(string $data): bool;
}