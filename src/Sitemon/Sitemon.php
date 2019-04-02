<?php

declare(strict_types=1);

namespace Sitemon;

use \Exception;
use Sitemon\Interfaces\ReportGeneratorInterface;

/**
 * Sitemon class to perform all benchmark tasks.
 * Can be called from console or from a web interface
 */
class Sitemon
{
    /**
     * [benchmark description]
     * @param  string                   $url             site to benchmark
     * @param  array                    $urls            sites to comapre to
     * @param  ReportGeneratorInterface $reportGenerator report generator
     * @return string                                    generated report or exception message
     */
    public function benchmark(string $url, array $urls, ReportGeneratorInterface $reportGenerator): string
    {
        try {
            // create new benchmark with initial CSV report to write it to a file
            $benchmark = new Benchmark(new CurlHttpClient(), new ReportGeneratorCSV());

            // add benchamrked site
            $benchmark->addUrl($url, true);

            // add rest URLs to benchmark
            $benchmark->addUrls($urls);

            // execute benchmark for all added URLs
            $benchmark->execute();

            $benchmark->storeReport(new FileWriter(['filename'=>'log.txt']));

            //$msg = new MessageEmail('test@example.com', $report, 'Report subject');
            //$msg->sendMessage();
            //
            //
            //$msg = new MessageText('+4844446663', $report);
            //$msg->sendMessage();

            // set report generator to display it in a web interface or return in command line
            $benchmark->setReportGenerator($reportGenerator);
            $report = $benchmark->getReport();
        } catch (Exception $ex) {
            return $ex->getMessage();
        }

        return $report;
    }
}
