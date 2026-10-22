--TEST--
Translated seed (inferredbugs-java: crawler-commons/1)
--FILE--
<?php

$maxUrls = 50000;
$maxBytesAllowed = 10485760;
$strict = true;

function processText($sitemapUrl, $content) {
    global $maxUrls, $strict;
    $lines = explode(PHP_EOL, $content);
    $i = 1;
    foreach ($lines as $line) {
        if (strlen($line) > 0 && $i <= $maxUrls) {
            try {
                $url = new URL(trim($line));
                $valid = urlIsLegal($sitemapUrl, $url);
                if ($valid || !$strict) {
                    echo "$i. $url\n";
                    $i++;
                }
            } catch (Exception $e) {
                // Handle bad URL
            }
        }
    }
}

function urlIsLegal($sitemapBaseUrl, $testUrl) {
    return strpos(strtolower($testUrl), strtolower($sitemapBaseUrl)) === 0;
}

$sitemapUrl = "http://example.com/sitemap.txt"; // Example sitemap URL
$content = file_get_contents($sitemapUrl); // Simulate fetching content

if ($content) {
    processText($sitemapUrl, $content);
}
--EXPECT--
