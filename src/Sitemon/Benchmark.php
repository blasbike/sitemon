<?php

declare(strict_types=1);

namespace Sitemon;

use Exception;
use Sitemon\BenchmarkResult;
use Sitemon\Interfaces\BenchmarkInterface;
use Sitemon\Interfaces\HttpClientInterface;
use Sitemon\Interfaces\ReportGeneratorInterface;

class Benchmark implements BenchmarkInterface
{
    /**
     * array of sites to benchmark
     * @var array
     */
    private $resultsQueue = [];


    /**
     * [$httpClient description]
     * @var HttpClientInterface
     */
    private $httpClient;


    /**
     * [$reportGenerator description]
     * @var ReportGeneratorInterface
     */
    private $reportGenerator;


    /**
     * [$benchmarkExecuted description]
     * @var boolean
     */
    private $benchmarkExecuted = false;


    /**
     * keeps copy of a result of benchmarked site
     * @var BenchmarkResult
     */
    private $benchmarkedSiteResult;


    /**
     * sets required http client and report generator
     * @param HttpClientInterface      $httpClient
     * @param ReportGeneratorInterface $reportGenerator
     */
    public function __construct(HttpClientInterface $httpClient, ReportGeneratorInterface $reportGenerator)
    {
        $this->httpClient = $httpClient;
        $this->reportGenerator = $reportGenerator;
    }


    /**
     * [setReportGenerator description]
     * @param ReportGeneratorInterface $reportGenerator [description]
     */
    public function setReportGenerator(ReportGeneratorInterface $reportGenerator): void
    {
        $this->reportGenerator = $reportGenerator;
    }


    /**
     * [addUrl description]
     * @param string       $url             [description]
     * @param bool|boolean $benchmarkedSite [description]
     */
	public function addUrl(string $url, bool $benchmarkedSite = false): void
    {
        if (!empty($url)) {
            $this->resultsQueue[] = new BenchmarkResult($url, $benchmarkedSite);
        }
    }


    /**
     * add multiple urls to the queue
     * @param array $urls [description]
     */
    public function addUrls(array $urls): void
    {
        foreach($urls as $url) {
            if (!empty($url)) {
                $this->resultsQueue[] = new BenchmarkResult($url);
            }
        }
    }


    /**
     * runs a report using selected report generator
     * @return string   generated report string
     */
    public function getReport(): string
    {
        if (!$this->benchmarkExecuted) {
            throw new Exception('Benchmark has not been executed.');
        }
        return $this->reportGenerator->generateReport($this->resultsQueue);
    }


    public function getBenchmarkedSiteResult(): BenchmarkResult
    {
        return $this->benchmarkedSiteResult;
    }


    public function setBenchmarkedSiteResult(BenchmarkResult $result): void
    {
        $this->benchmarkedSiteResult = $result;
    }


    /**
     * [execute description]
     * @return [type] [description]
     */
	public function execute(): void
    {
        foreach ($this->resultsQueue as $result) {
            $this->executeSingle($result);
        }
        $this->benchmarkExecuted = true;
    }


    /**
     * [executeSingle description]
     * @param  BenchmarkResult $result [description]
     * @return [type]                  [description]
     */
    private function executeSingle(BenchmarkResult $result): void
    {
        $time_start = microtime(true);

        $httpResult = $this->httpClient->get($result->getSiteUrl());

        $time_end = microtime(true);

        $loadingTime = $time_end - $time_start;

        $result->setHttpCode($httpResult['code']);
        $result->setSize($httpResult['size']);
        $result->setLoadingTime($loadingTime);

        // store a result for time differences calculations
        if ($result->isBenchmarkedSite()) {
            $this->setBenchmarkedSiteResult($result);
        }

        $result->setDiffToBenchmarkedSite($result->getLoadingTime() - $this->getBenchmarkedSiteResult()->getLoadingTime());
    }
}
