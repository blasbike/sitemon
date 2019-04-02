<?php

declare(strict_types=1);

namespace Sitemon\Interfaces;

interface DataStorageInterface
{
    public function storeData(string $data): bool;
}