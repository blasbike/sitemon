<?php

declare(strict_types=1);

namespace Sitemon;

use \Exception;
use Sitemon\BenchmarkResult;
use Sitemon\Interfaces\BenchmarkInterface;
use Sitemon\Interfaces\HttpClientInterface;
use Sitemon\Interfaces\ReportGeneratorInterface;
use Sitemon\Interfaces\DataStorageInterface;
use Sitemon\Interfaces\MessageInterface;

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
     * generated report
     * @var string
     */
    private $generatedReport;


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


    public function getReportGenerator(): ReportGeneratorInterface
    {
        return $this->reportGenerator;
    }


    public function setResultsQueue(array $queue): void
    {
        $this->resultsQueue = $queue;
    }


    public function getResultsQueue(): array
    {
        return $this->resultsQueue;
    }


    public function getBenchmarkExecuted(): bool
    {
        return $this->benchmarkExecuted;
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
            $result = new BenchmarkResult($url, $benchmarkedSite);
            $this->resultsQueue[] = $result;
            if ($benchmarkedSite) {
                $this->setBenchmarkedSiteResult($result);
            }
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
     * @param  ReportGeneratorInterface $reportGenerator
     * @return string                                    generated report string
     */
    public function generateReport(ReportGeneratorInterface $reportGenerator): string
    {
        if (!$this->benchmarkExecuted) {
            throw new Exception('Benchmark has not been executed.');
        }
        $this->setGeneratedReport($reportGenerator->generateReport($this->resultsQueue));
        return $this->generatedReport;
    }


    public function setGeneratedReport(string $report): void
    {
        $this->generatedReport = $report;
    }


    public function getGeneratedReport(): ?string
    {
        return $this->generatedReport;
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
     * then generates a report using required default report generator
     * @return void
     */
	public function execute(): void
    {
        $this->resultsQueue = array_map([Benchmark::class, 'executeSingle'], $this->resultsQueue);
        $this->benchmarkExecuted = true;
        $this->generateReport($this->reportGenerator);
    }


    /**
     * executes single site benchmark
     * @param  BenchmarkResult $result
     * @return BenchmarkResult
     */
    private function executeSingle(BenchmarkResult $result): BenchmarkResult
    {
        $httpResult = $this->httpClient->get($result->getSiteUrl());

        $result->setHttpCode($httpResult['code']);
        $result->setSize($httpResult['size']);
        $result->setLoadingTime($httpResult['time']);

        $siteres = $this->getBenchmarkedSiteResult();
        if ($siteres) {
            $result->setDiffToBenchmarkedSite($result->getLoadingTime() - $siteres->getLoadingTime());
        }

        return $result;
    }


    /**
     * [storeReport description]
     * @param  DataStorageInterface $store [description]
     * @return [type]                    [description]
     */
    public function storeReport(DataStorageInterface $store): void
    {
        $store->storeData($this->getGeneratedReport());
    }


    public function processMessages(MessageInterface $email, MessageInterface $sms): void
    {

        if ($this->isResultSlowerXTimesThenXOthers($this->getBenchmarkedSiteResult(), 1, 1)) {
            $email->setSubject(sprintf('Benchmarked site %s is slower', $this->getBenchmarkedSiteResult()->getSiteUrl()));
            $email->setMessage($this->getGeneratedReport());
            $email->sendMessage();
        }

        /**
         * TODO;-) $result->isSlower()->xTimes(2)->thenOther(2)
         */
        if ($this->isResultSlowerXTimesThenXOthers($this->getBenchmarkedSiteResult(), 2, 1)) {
            $sms->setMessage($this->getGeneratedReport());
            $sms->sendMessage();
        }

    }


    /**
     * checks if given result is $xTimes slower then at least $xOthers results
     * @param  BenchmarkResult $result
     * @param  int             $xTimes
     * @param  int             $xOthers
     * @return boolean
     */
    public function isResultSlowerXTimesThenXOthers(BenchmarkResult $result, int $xTimes, int $xOthers): bool
    {
        $xOtherTimes = 0;
        foreach ($this->resultsQueue as $otherResult) {
            if ($result->getLoadingTime() > $xTimes * $otherResult->getLoadingTime()) {
                $xOtherTimes++;
            }
            if ($xOtherTimes >= $xOthers) {
                return true;
            }
        }
        return false;
    }    
}
