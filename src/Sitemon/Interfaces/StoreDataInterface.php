<?php

declare(strict_types=1);

namespace Sitemon\Interfaces;

interface StoreDataInterface
{
    public function storeData(string $data): bool;
}