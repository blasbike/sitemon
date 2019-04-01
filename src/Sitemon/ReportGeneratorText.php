<?php

declare(strict_types=1);

namespace Sitemon;

use Sitemon\Interfaces\ReportGeneratorInterface;

class ReportGeneratorText implements ReportGeneratorInterface
{
    /**
     * [generateReport description]
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function generateReport(array $data): string
    {
        $text = 'Report time: ' . date('Y-m-d H:i:s') . PHP_EOL;
        foreach($data as $result) {

            $text .= $result->getLoadingTime() . 's' . "\t:" .
                     $result->getSiteUrl() . ' (' . $result->getDiffToBenchmarkedSite() . 's)' . PHP_EOL;
        }
        return $text;
    }
}
