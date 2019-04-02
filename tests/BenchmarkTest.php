<?php

declare(strict_types=1);

namespace Sitemon;

use PHPUnit\Framework\TestCase;

class BenchmarkTest extends TestCase
{
    /**
     * @test
     */
    public function addUrl()
    {
        $benchmark = new Benchmark(new CurlHttpClient(), new ReportGeneratorText());

        $benchmark->addUrl('example.com', true);

        $queue = $benchmark->getResultsQueue();
        $res = array_pop($queue);

        $this->assertTrue($res instanceof BenchmarkResult);
        $this->assertSame('example.com', $res->getSiteUrl());
        $this->assertTrue($res->isBenchmarkedSite());
    }


    /**
     * testing benchamrk execute with a stub for httpClient->get()
     * fast testing without actually using httpClient and cURL
     *
     * @test
     */
    public function isResultSlowerXTimesThenXOthers()
    {

        $client = $this->createMock('Sitemon\CurlHttpClient', ['get']);

        $client->expects($this->at(0))
            ->method('get')
            ->with('php.net')
            ->will($this->returnValue(['code'=>200,'size'=>23412, 'time'=>0.412]));

        $client->expects($this->at(1))
            ->method('get')
            ->with('example.com')
            ->will($this->returnValue(['code'=>301,'size'=>1412, 'time'=>0.115]));

        $client->expects($this->at(2))
            ->method('get')
            ->with('bbc.com')
            ->will($this->returnValue(['code'=>404,'size'=>41412, 'time'=>0.203]));


        $benchmark = new Benchmark($client, new ReportGeneratorText());

        $benchmark->addUrl('php.net', true);
        $benchmark->addUrl('example.com');
        $benchmark->addUrl('bbc.com');

        $benchmark->execute();

        $benchmark->storeReport(new FileWriter(['filename'=>'log.txt']));

        $this->assertTrue($benchmark->getBenchmarkExecuted());


        $this->assertTrue($benchmark->isResultSlowerXTimesThenXOthers(
                $benchmark->getBenchmarkedSiteResult(), 2, 1
            ));

        $this->assertTrue($benchmark->isResultSlowerXTimesThenXOthers(
                $benchmark->getBenchmarkedSiteResult(), 3, 1
            ));

        $this->assertFalse($benchmark->isResultSlowerXTimesThenXOthers(
                $benchmark->getBenchmarkedSiteResult(), 4, 1
            ));

        $report = $benchmark->getGeneratedReport();
        $this->assertStringContainsString('example.com', $report);
        $this->assertStringContainsString('php.net', $report);
    }


    /**
     * Actual full test of all benchmark tasks
     * @test
     */
    public function execute()
    {
        $benchmark = new Benchmark(new CurlHttpClient(), new ReportGeneratorText());

        $benchmark->addUrl('example.com', true);
        $benchmark->addUrl('php.net');


        $queue = $benchmark->execute();

        $report = $benchmark->getGeneratedReport();
        $this->assertStringContainsString('example.com', $report);
        $this->assertStringContainsString('php.net', $report);


        $benchmark->storeReport(new FileWriter(['filename'=>'log.txt']));

        $this->assertTrue($benchmark->getBenchmarkExecuted());
        $this->assertInstanceOf(ReportGeneratorText::class, $benchmark->getReportGenerator());
        $this->assertFileExists('log.txt');
    }
}
