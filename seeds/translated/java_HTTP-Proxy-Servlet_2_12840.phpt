--TEST--
Translated seed (inferredbugs-java: HTTP-Proxy-Servlet/2)
--FILE--
<?php

$doLog = false;
$doForwardIP = true;
$doSendUrlFragment = true;
$targetUri = "http://example.com"; // Set your target URI here

function encodeUriQuery($in) {
    $outBuf = '';
    $length = strlen($in);
    for ($i = 0; $i < $length; $i++) {
        $c = $in[$i];
        if (ord($c) < 128 && preg_match('/[a-zA-Z0-9_\-!.~\'()*]/', $c)) {
            $outBuf .= $c;
        } else {
            $outBuf .= sprintf('%%%02X', ord($c));
        }
    }
    return $outBuf;
}

function rewriteUrlFromRequest($requestUri, $queryString) {
    global $targetUri, $doSendUrlFragment;
    $uri = $targetUri;

    if ($requestUri) {
        $uri .= encodeUriQuery($requestUri);
    }

    if ($queryString) {
        parse_str($queryString, $params);
        if (!empty($params)) {
            $queryNoFrag = http_build_query($params);
            $uri .= '?' . encodeUriQuery($queryNoFrag);
        }
        if ($doSendUrlFragment && strpos($queryString, '#') !== false) {
            $fragment = explode('#', $queryString)[1];
            $uri .= '#' . encodeUriQuery($fragment);
        }
    }

    return $uri;
}

$requestUri = "/my/path.html";
$queryString = "name=value&foo=bar#fragment";

$rewrittenUrl = rewriteUrlFromRequest($requestUri, $queryString);

// Simulate logging the rewritten URL
if ($doLog) {
    echo "Rewritten URL: " . $rewrittenUrl . PHP_EOL;
}

// The script simulates the process without errors under a correct implementation.
--EXPECT--
