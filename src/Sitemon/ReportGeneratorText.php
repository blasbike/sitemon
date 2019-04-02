<?php

declare(strict_types=1);

namespace Sitemon;

use Sitemon\Interfaces\ReportGeneratorInterface;

class ReportGeneratorText implements ReportGeneratorInterface
{
    /**
     * generates text report, can be used in console
     *
     * @param  array  $data array of BenchmarkResult rows
     * @return string
     */
    public function generateReport(array $data): string
    {
        $text = 'Report time: ' . date('Y-m-d H:i:s') . PHP_EOL;
        foreach($data as $result) {
            $text .= $this->renderRow($result);
        }
        return $text;
    }


    /**
     * generates single row of a report
     *
     * @param  BenchmarkResult $result
     * @return string
     */
    protected function renderRow(BenchmarkResult $result): string
    {
        return '[status: ' . $result->getHttpCode() . '] ' .
             $result->getLoadingTime() . 's' . "\t:" .
             $result->getSiteUrl() . ' (' .
             ($result->getDiffToBenchmarkedSite() > 0 ? '+' : '' ) .
             $result->getDiffToBenchmarkedSite() . 's)' .
             PHP_EOL;
    }
}
