<?php

declare(strict_types=1);

namespace Sitemon;

use Sitemon\Interfaces\ConditionInterface;

class ConditionSlowerThenOthers implements ConditionInterface
{
    /**
     * array of BenchmarkResult
     * @var array
     */
    private $results;


    /**
     * benchamrked site result
     * @var BenchmarkResult
     */
    private $benchmarkedSiteResult;


    /**
     * x times slowet then others
     * @var int
     */
    private $xTimes;


    /**
     * slower then x other sites
     * @var [type]
     */
    private $xOthers;


    /**
     * sets up a new condition with condition parameters
     * @param array           $results               [description]
     * @param BenchmarkResult $benchmarkedSiteResult [description]
     * @param int             $xTimes                [description]
     * @param int             $xOthers               [description]
     */
    public function __construct(array $results, BenchmarkResult $benchmarkedSiteResult, int $xTimes, int $xOthers)
    {
        $this->results = $results;
        $this->benchmarkedSiteResult = $benchmarkedSiteResult;
        $this->xTimes = $xTimes;
        $this->xOthers = $xOthers;
    }


    /**
     * main condition definition checks if defined condition is met
     * checks if given result is $xTimes slower then at least $xOthers results
     * 
     * @return bool true if condition is met
     */
    public function condition(): bool
    {
        $xOtherTimes = 0;
        foreach ($this->results as $otherResult) {
            if ($this->benchmarkedSiteResult->getLoadingTime() > $this->xTimes * $otherResult->getLoadingTime()) {
                $xOtherTimes++;
            }
            if ($xOtherTimes >= $this->xOthers) {
                return true;
            }
        }
        return false;
    }
}
