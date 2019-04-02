<?php

declare(strict_types=1);

namespace Sitemon\Interfaces;


interface HttpClientInterface
{
    public function get(string $url): array;
}
