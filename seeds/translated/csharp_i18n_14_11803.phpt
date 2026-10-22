--TEST--
Translated seed (inferredbugs-csharp: i18n/14)
--FILE--
<?php
$rawUrl = "/WebResource.axd?x=1";
$contentType = "text/html";
$headers = [];
$headers["Content-Encoding"] = null;
$urlsToExclude = '/exclude/i';
$contentTypesToLocalize = '/text\/html/i';

if (preg_match($contentTypesToLocalize, $contentType)) {
    if (preg_match($urlsToExclude, $rawUrl)) {
    } elseif (($headers["Content-Encoding"] ?? null) !== null || ($headers["Content-Encoding"] ?? null) === "gzip") {
    } else {
        $responseFilter = [
            "url" => $rawUrl,
            "contentType" => $contentType,
            "encoding" => $headers["Content-Encoding"] ?? null
        ];
    }
}

if (stripos($rawUrl, "/webresource.axd") !== false) {
    $ignoringFurtherWrites = true;
    $ignoringFurtherWrites = false;
}

echo "ok\n";
--EXPECT--
