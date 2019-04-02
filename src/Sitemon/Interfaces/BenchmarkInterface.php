<?php

declare(strict_types=1);

namespace Sitemon\Interfaces;

interface BenchmarkInterface
{
    /**
     * executes benchmark for all URLs
     * @return void
     */
	public function execute(): void;


    /**
     * adds URL to benchmark
     * @param string $url [description]
     */
    public function addUrl(string $url): void;
}
