<?php

declare(strict_types=1);

namespace Sitemon;

use \Exception;
use Sitemon\BenchmarkResult;
use Sitemon\Interfaces\BenchmarkInterface;
use Sitemon\Interfaces\HttpClientInterface;
use Sitemon\Interfaces\ReportGeneratorInterface;
use Sitemon\Interfaces\StoreDataInterface;

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
     * sets report generator used for output reports
     *
     * @param ReportGeneratorInterface $reportGenerator
     */
    public function setReportGenerator(ReportGeneratorInterface $reportGenerator): void
    {
        $this->reportGenerator = $reportGenerator;
    }


    public function setResultsQueue(array $queue): void
    {
        $this->resultsQueue = $queue;
    }


    public function getResultsQueue(array $queue): array
    {
        return $this->resultsQueue;
    }


    /**
     * adds a site's URL to the benchmark queue
     *
     * @param string       $url             URL with or without protocol specified
     * @param bool|boolean $benchmarkedSite a site that is tested agains another sites
     */
	public function addUrl(string $url, bool $benchmarkedSite = false): void
    {
        if (!empty($url)) {
            $this->resultsQueue[] = new BenchmarkResult($url, $benchmarkedSite);
        }
    }


    /**
     * add multiple urls to the queue
     * 
     * @param array $urls array of string urls
     */
    public function addUrls(array $urls): void
    {
        foreach($urls as $url) {
            $this->addUrl($url);
        }
    }


    /**
     * runs a report using selected report generator
     * 
     * @return string   generated report string
     */
    public function getReport(): string
    {
        if (!$this->benchmarkExecuted) {
            throw new Exception('Benchmark has not been executed.');
        }
        return $this->reportGenerator->generateReport($this->resultsQueue);
    }


    /**
     * 
     * @return BenchmarkResult
     */
    public function getBenchmarkedSiteResult(): BenchmarkResult
    {
        return $this->benchmarkedSiteResult;
    }


    /**
     * 
     * @param BenchmarkResult $result
     */
    public function setBenchmarkedSiteResult(BenchmarkResult $result): void
    {
        $this->benchmarkedSiteResult = $result;
    }


    /**
     * executes benchmark for all sites added to the queue
     * @return void
     */
	public function execute(): void
    {
        $this->resultsQueue = array_map([Benchmark::class, 'executeSingle'], $this->resultsQueue);
        $this->benchmarkExecuted = true;
    }


    /**
     * executes single site benchmark
     * @param  BenchmarkResult $result
     * @return BenchmarkResult
     */
    private function executeSingle(BenchmarkResult $result): BenchmarkResult
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

        $siteres = $this->getBenchmarkedSiteResult();
        if ($siteres) {
            $result->setDiffToBenchmarkedSite($result->getLoadingTime() - $siteres->getLoadingTime());
        }

        return $result;
    }


    /**
     * [storeReport description]
     * @param  StoreDataInterface $store [description]
     * @return [type]                    [description]
     */
    public function storeReport(StoreDataInterface $store): void
    {
        $store->storeData($this->getReport());
    }
}
