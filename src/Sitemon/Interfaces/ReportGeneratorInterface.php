<?php

declare(strict_types=1);

namespace Sitemon\Interfaces;

interface ReportGeneratorInterface
{
    public function generateReport(array $data): string;
}