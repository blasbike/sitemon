<?php

declare(strict_types=1);

namespace Sitemon;

use Sitemon\Interfaces\ReportGeneratorInterface;

class ReportGeneratorCSV implements ReportGeneratorInterface
{
    /**
     * [generateReport description]
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function generateReport(array $data): string
    {
        $csv = 'Report time: ' . date('Y-m-d H:i:s') . PHP_EOL;
        foreach($data as $result) {

            $csv .= $result->getSiteUrl() . ',' .
                    $result->getLoadingTime() . ',' .
                    ($result->getDiffToBenchmarkedSite() > 0 ? '+' : '' ) . 
                    $result->getDiffToBenchmarkedSite() . PHP_EOL;
        }
        return $csv;
    }
}
