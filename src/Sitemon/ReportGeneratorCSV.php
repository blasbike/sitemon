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
        $csv = '';
        foreach($data as $result) {

            $csv .= $result->getHttpCode() . ',' .
                    $result->getSiteUrl() . ',' .
                    $result->getLoadingTime() . ',' .
                    $result->isBenchmarkedSite() . PHP_EOL;
        }
        return $csv;
    }
}
