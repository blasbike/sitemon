<?php

declare(strict_types=1);

namespace Sitemon;

use PHPUnit\Framework\TestCase;

class BenchmarkResultTest extends TestCase
{
    /**
     * @test
     */
    public function getLoadingTime()
    {
        $result = new BenchmarkResult('example.com', true);
        $result->setLoadingTime(0.45678987);
        $this->assertSame(0.5, $result->getLoadingTime(1));
        $this->assertSame(0.46, $result->getLoadingTime(2));
        $this->assertSame(0.457, $result->getLoadingTime(3));
        $this->assertSame(0.4568, $result->getLoadingTime(4));
        $this->assertTrue(0.45679 === $result->getLoadingTime(5));
    }


    /**
     * @test
     */
    public function constructor()
    {
        $result = new BenchmarkResult('example.com', true);
        $this->assertTrue('example.com' === $result->getSiteUrl());
        $this->assertTrue($result->isBenchmarkedSite());

        $result = new BenchmarkResult('example.com');
        $this->assertFalse($result->isBenchmarkedSite());
    }


    /**
     * @test
     */
    public function setDiffToBenchmarkedSite()
    {
        $result = new BenchmarkResult('example.com');
        $result->setDiffToBenchmarkedSite(-0.1568);
        $this->assertSame(-0.1568, $result->getDiffToBenchmarkedSite());
    }
}
