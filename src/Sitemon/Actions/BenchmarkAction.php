<?php

declare(strict_types=1);

namespace Sitemon\Actions;

use Sitemon\Sitemon;
use Sitemon\CurlHttpClient;
use Sitemon\Benchmark;
use Sitemon\ReportGeneratorHTML;
use Sitemon\ReportGeneratorCSV;
use Sitemon\FileWriter;
use Sitemon\MessageEmail;

class BenchmarkAction
{
    /**
     * run web action
     * @param  array $params query parameters
     * @return string        HTML to display
     */
    public function run(array $params): string
    {

        $sitemon = new Sitemon();

        $benchamrkedSite = $params[1];

        // prepare other sites URLs
        $otherSites = [];
        if (isset($params[2])) {
            $otherSites = explode(PHP_EOL, $params[2]);

            if (!is_array($otherSites)) {
                $otherSites = [];
            }
        }

        $report = $sitemon->benchmark($benchamrkedSite, $otherSites, new ReportGeneratorHTML());

        return $report;
    }


    /**
     * Generates HTML form
     * @return string   HTML to display
     */
    public function index(): string
    {
        include '../src/Sitemon/Views/InputForm.php';
        return '';
    }
}
