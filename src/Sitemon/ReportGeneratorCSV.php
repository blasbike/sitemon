<?php

declare(strict_types=1);

namespace Sitemon;

use Sitemon\Interfaces\ReportGeneratorInterface;

class ReportGeneratorCSV implements ReportGeneratorInterface
{
    /**
     * generates CSV report, can be used in a file
     *
     * @param  array  $data array of BenchmarkResult
     * @return string       CSV string
     */
    public function generateReport(array $data): string
    {
        $csv = 'Report time: ' . date('Y-m-d H:i:s') . PHP_EOL;
        foreach($data as $result) {
            $csv .= $this->renderRow($result);
        }
        return $csv;
    }


    /**
     * generates single CSV row of a report
     *
     * @param  BenchmarkResult $result
     * @return string
     */
    protected function renderRow(BenchmarkResult $result): string
    {
        return  $result->getSiteUrl() . ',' .
                $result->getLoadingTime() . ',' .
                ($result->getDiffToBenchmarkedSite() > 0 ? '+' : '' ) .
                $result->getDiffToBenchmarkedSite() . PHP_EOL;
    }
}
