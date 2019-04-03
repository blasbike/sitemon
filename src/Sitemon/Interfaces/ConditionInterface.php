<?php

declare(strict_types=1);

namespace Sitemon\Interfaces;

interface ConditionInterface
{
    public function condition(): bool;
}
