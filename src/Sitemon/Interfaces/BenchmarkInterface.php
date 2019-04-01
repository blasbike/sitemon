<?php

declare(strict_types=1);

namespace Sitemon\Interfaces;

interface BenchmarkInterface
{
    /**
     * [execute description]
     * @return [type] [description]
     */
	public function execute(): void;


    /**
     * [addUrl description]
     * @param string $url [description]
     */
    public function addUrl(string $url): void;


    /**
     * [getReport description]
     * @return [type] [description]
     */
    public function getReport(): string;
}