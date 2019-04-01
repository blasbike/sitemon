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
        $html = '<div>Report time: ' . date('Y-m-d H:i:s') . '</div>
        <table>
            <tr>
                <th>HTTP status</th>
                <th>Loading time</th>
                <th>URL</th>
                <th>Time diff</th>
            </tr>';
        foreach($data as $result) {
            $html .=
            '<tr>
                <td width="100">' . $result->getHttpCode() . '</td>
                <td width="100" align="right">' . $result->getLoadingTime() . '</td>
                <td width="100" align="right">' . $result->getSiteUrl() . '</td>
                <td width="100" align="right">' . 
                    ($result->getDiffToBenchmarkedSite() > 0 ? '+' : '' ) .
                    $result->getDiffToBenchmarkedSite() . 's
                </td>
            </tr>';
        }
        $html .= '
        </table>';
        return $html;
    }
}
