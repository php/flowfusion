--TEST--
Translated seed (inferredbugs-java: crawler-commons/2)
--FILE--
<?php

class SiteMap {
    private $urls = [];
    private $baseUrl;

    public function __construct($baseUrl) {
        $this->baseUrl = $baseUrl;
    }

    public function addUrl($url) {
        $this->urls[] = $url;
    }

    public function getUrls() {
        return $this->urls;
    }
}

function isValidUrl($baseUrl, $testUrl) {
    return strpos($testUrl, $baseUrl) === 0;
}

function parseSitemap($content, $baseUrl) {
    $sitemap = new SiteMap($baseUrl);
    $lines = explode("\n", $content);
    $i = 1;

    foreach ($lines as $line) {
        if (strlen($line) > 0 && $i <= 50000) {
            if (isValidUrl($baseUrl, $line)) {
                $sitemap->addUrl($line);
            }
            $i++;
        }
    }

    return $sitemap;
}

$content = "http://www.example.com/\nhttp://www.example.com/catalog?item=12&desc=vacation_hawaii\nhttp://www.invalidurl.com/";
$baseUrl = "http://www.example.com/";

$sitemap = parseSitemap($content, $baseUrl);
foreach ($sitemap->getUrls() as $url) {
    echo $url . "\n";
}
--EXPECT--
