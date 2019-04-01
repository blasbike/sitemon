<?php

declare(strict_types=1);

namespace Sitemon\Actions;

use Sitemon\Actions\AbstractAction;
use Sitemon\Sitemon;
use Sitemon\CurlHttpClient;
use Sitemon\Benchmark;
use Sitemon\ReportGeneratorHTML;
use Sitemon\ReportGeneratorCSV;
use Sitemon\FileWriter;
use Sitemon\MessageEmail;

class BenchmarkAction extends AbstractAction
{
    /**
     * run web action
     * @param  array $params query parameters
     * @return string        HTML to display
     */
    public function run(array $params): void
    {

        $sitemon = new Sitemon();

        $benchamrkedSite = $params['url'];

        // prepare other sites URLs
        $otherSites = [];
        if (isset($params['otherurls'])) {
            $otherSites = explode(PHP_EOL, $params['otherurls']);

            if (!is_array($otherSites)) {
                $otherSites = [];
            }
        }

        $report = $sitemon->benchmark($benchamrkedSite, $otherSites, new ReportGeneratorHTML());
        self::loadView('Report', ['report'=>$report]);
    }


    /**
     * Generates HTML form
     * @return string   HTML to display
     */
    public function index(): void
    {
        self::loadView('InputForm');
    }
}
