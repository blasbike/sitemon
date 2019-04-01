<?php

declare(strict_types=1);

namespace Sitemon;


class BenchmarkResult
{
    private $siteUrl;


    private $loadingTime;


    private $size;


    private $httpCode;


    private $benchmarkedSite = false;


    private $diffToBenchmarkedSite;


    public function __construct(string $siteUrl, bool $benchmarkedSite = false)
    {
        $this->siteUrl = $siteUrl;
        $this->benchmarkedSite = $benchmarkedSite;
    }


    public function isBenchmarkedSite(): bool
    {
        return $this->benchmarkedSite;
    }


    public function setDiffToBenchmarkedSite(float $timediff): void
    {
        $this->diffToBenchmarkedSite = $timediff;
    }


    public function getSiteUrl(): string
    {
        return $this->siteUrl;
    }


	public function getHttpCode(): int
    {
        return $this->httpCode;
    }


	public function getLoadingTime(int $roundPrecision = 2): float
    {
        return round($this->loadingTime, $roundPrecision);
    }


	public function getSize(): int
    {
        return $this->size;
    }


    public function getDiffToBenchmarkedSite(): float
    {
        return $this->diffToBenchmarkedSite;
    }


    public function setHttpCode(int $httpCode): void
    {
        $this->httpCode = $httpCode;
    }


    public function setLoadingTime(float $loadingTime): void
    {
        $this->loadingTime = $loadingTime;
    }


    public function setSize(int $size): void
    {
        $this->size = $size;
    }
}
