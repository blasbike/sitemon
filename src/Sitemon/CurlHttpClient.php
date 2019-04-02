<?php

declare(strict_types=1);

namespace Sitemon;

use \Exception;
use Sitemon\Interfaces\HttpClientInterface;

class CurlHttpClient implements HttpClientInterface
{
    /**
     * fetches provided URL
     *
     * @param  string $url  website URL to fetch
     * @return array        set of parameters like HTTP status code(code) and size of fetched page(size)
     * @throws Exception    if $url is empty or return cURL response is false
     */
    public function get(string $url): array
    {
        if (empty($url)) {
            throw new Exception("Site URL is required");
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; Firefox)');
        // return transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        // include headers in the returntransfer
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        // follow redirect eg from http to https
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        // returned webpage with headers
        $siteReturn = curl_exec($ch);

        if ($siteReturn === false) {
            //throw new Exception(sprintf('Site "%s" can not be retrived', $url));
        }

        $siteSize = strlen($siteReturn);

        // get http code
        $siteStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ['code'=>$siteStatus, 'size'=>$siteSize];
    }
}
