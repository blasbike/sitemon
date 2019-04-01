<?php

declare(strict_types=1);

namespace Sitemon;

use Sitemon\Interfaces\ReportGeneratorInterface;

class ReportGeneratorHTML implements ReportGeneratorInterface
{
    /**
     * generates HTML string
     * @param  array  $data [description]
     * @return string       HTML string
     */
    public function generateReport(array $data): string
    {
        $html = '<div>Report time: ' . date('Y-m-d H:i:s') . '</div><pre>';
        foreach($data as $result) {

            $html .=  $result->getLoadingTime() . 's' . ': ' .
                      $result->getSiteUrl() . ' (' . $result->getDiffToBenchmarkedSite() . 's)' . PHP_EOL;
        }
        $html .= '</pre>';
        return $html;
    }
}
