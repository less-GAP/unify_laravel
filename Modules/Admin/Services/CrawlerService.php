<?php


namespace Modules\Admin\Services;

use Symfony\Component\DomCrawler\Crawler;

class CrawlerService
{
    public function initDomCrawler($url)
    {
        $httpClient = new \GuzzleHttp\Client();
        $response = $httpClient->get($url);
        $htmlString = (string)$response->getBody();
        return new Crawler($htmlString);
    }

    public function filterXPath(Crawler $crawler, $rule)
    {
        return $crawler->filterXPath($rule);
    }
    public function listText(Crawler $crawler)
    {
        return $crawler->each(function (Crawler $node, $i): string {
            return $node->text();
        });
    }
}
