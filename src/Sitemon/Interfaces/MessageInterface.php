<?php

declare(strict_types=1);

namespace Sitemon\Interfaces;

interface MessageInterface
{
    public function sendMessage(): bool;
}
