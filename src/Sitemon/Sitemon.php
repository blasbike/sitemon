<?php

declare(strict_types=1);

namespace Sitemon;


use Sitemon\Interfaces\ReportGeneratorInterface;
use Sitemon\Interfaces\FileWriterInterface;

class Sitemon
{
    /**
     * [benchmark description]
     * @param  string                   $url             [description]
     * @param  array                    $urls            [description]
     * @param  ReportGeneratorInterface $reportGenerator [description]
     * @return [type]                                    [description]
     */
    public function benchmark(string $url, array $urls, ReportGeneratorInterface $reportGenerator): string
    {
        // create new benchmark with initial CSV report to write it to a file
        $benchmark = new Benchmark(new CurlHttpClient(), new ReportGeneratorCSV());

        // add benchamrked site
        $benchmark->addUrl($url, true);

        // add rest URLs to benchmark
        $benchmark->addUrls($urls);

        // execute benchmark for all added URLs
        $benchmark->execute();

        // generate report from the initial report generator
        $report = $benchmark->getReport();

        $fileWriter = new FileWriter(['filename'=>'log.txt']);
        $fileWriter->writeFile($report);

        //$msg = new MessageEmail('test@example.com', $report, 'Report subject');
        //$msg->sendMessage();
        //
        //$msg = new MessageText('', $report, 'temat raport');
        //$msg->sendMessage();

        // set report generator to display it in a web interface or return in command line
        $benchmark->setReportGenerator($reportGenerator);
        $report = $benchmark->getReport();

        return $report;
    }
}
