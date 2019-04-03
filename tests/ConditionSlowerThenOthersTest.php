<?php

declare(strict_types=1);

namespace Sitemon;

use PHPUnit\Framework\TestCase;


class ConditionSlowerThenOthersTest extends TestCase
{
    /**
     *
     * @test
     */
    public function condition()
    {
        $benchmarkedSiteRes = new BenchmarkResult('php.net', true, 0.412, 23432, 200);

         $res =   [$benchmarkedSiteRes,
                   new BenchmarkResult('example.com', false, 0.115, 1432, 301),
                   new BenchmarkResult('bbc.com', false, 0.203, 43432, 404)];

        $cond1 = new ConditionSlowerThenOthers($res, $benchmarkedSiteRes, 2, 1);

        $cond2 = new ConditionSlowerThenOthers($res, $benchmarkedSiteRes, 3, 1);

        $cond3 = new ConditionSlowerThenOthers($res, $benchmarkedSiteRes, 4, 1);

        $this->assertTrue($cond1->condition());
        $this->assertTrue($cond2->condition());
        $this->assertFalse($cond3->condition());
    }
}