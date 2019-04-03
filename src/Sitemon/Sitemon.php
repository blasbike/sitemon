<?php

declare(strict_types=1);

namespace Sitemon;

use \Exception;
use Sitemon\Interfaces\ReportGeneratorInterface;
use Sitemon\Notifier;
use Sitemon\ConditionSlowerThenOthers;

/**
 * Sitemon class to perform all benchmark tasks.
 * Can be called from console or from a web interface
 */
class Sitemon
{
    /**
     * set of tasks to perform a full benchmark
     * @param  string                   $url             site to benchmark
     * @param  array                    $urls            sites to comapre to
     * @param  ReportGeneratorInterface $reportGenerator report generator
     * @return string                                    generated report or exception message
     */
    public function benchmark(string $url, array $urls, ReportGeneratorInterface $reportGenerator): string
    {
        try {
            // create new benchmark with initial Text report
            $benchmark = new Benchmark(new CurlHttpClient(), new ReportGeneratorText());

            // add benchamrked site
            $benchmark->addUrl($url, true);

            // add rest URLs to benchmark
            $benchmark->addUrls($urls);

            // execute benchmark for all added URLs
            $benchmark->execute();

            $report = $benchmark->getGeneratedReport();

            // store report in a file
            $storage = new FileWriter(['filename'=>'log.txt']);
            $storage->storeData($report);


            // sends notifications if conditions are met
            $notifier = new Notifier();
            // set up two different conditions for notifier
            $notifier->addNotification(
                new MessageEmail('blase@bikestats.pl', 'Site is slower', $report),
                new ConditionSlowerThenOthers(
                    $benchmark->getResultsQueue(),
                    $benchmark->getBenchmarkedSiteResult(), 1, 1)
            );
            $notifier->addNotification(
                new MessageText('+482222333', $report),
                new ConditionSlowerThenOthers(
                    $benchmark->getResultsQueue(),
                    $benchmark->getBenchmarkedSiteResult(), 2, 1)
            );
            $notifier->notify();

            // generates a report with a new generator to display it in a web interface or return in command line
            $report = $benchmark->generateReport($reportGenerator);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }

        return $report;
    }
}
