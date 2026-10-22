--TEST--
Translated seed (inferredbugs-java: FluentLenium/2)
--FILE--
<?php

function concat($baseUriSpec, $uriSpec) {
    if ($baseUriSpec !== null && substr($baseUriSpec, -1) !== '/') {
        $baseUriSpec .= '/';
    }
    $baseUri = null;
    if ($baseUriSpec !== null) {
        $baseUri = parse_url($baseUriSpec);
    }

    if ($baseUri !== null && $uriSpec !== null && strpos($uriSpec, '/') === 0) {
        $uriSpec = substr($uriSpec, 1);
    }

    $uri = null;
    if ($uriSpec !== null) {
        $uri = parse_url($uriSpec);
    }

    if ($baseUri !== null && $uri !== null) {
        return $baseUri['scheme'] . '://' . $baseUri['host'] . '/' . trim($uriSpec, '/');
    } else if ($baseUri !== null) {
        return $baseUriSpec;
    } else if ($uri !== null) {
        return $uriSpec;
    } else {
        return null;
    }
}

function sanitizeBaseUrl($baseUriSpec, $uriSpec) {
    if ($baseUriSpec === null) {
        return null;
    }

    $baseUri = parse_url($baseUriSpec);

    if (!isset($baseUri['scheme'])) {
        while (substr($baseUriSpec, 0, 2) !== '//') {
            $baseUriSpec = '/' . ltrim($baseUriSpec, '/');
        }
        $baseUri = parse_url('http://' . $baseUriSpec);
    }

    $uri = $uriSpec === null ? null : parse_url($uriSpec);
    $scheme = ($uri === null || (!isset($baseUri['host']) || $baseUri['host'] !== $uri['host'])) ? $baseUri['scheme'] : $uri['scheme'];

    if ($scheme !== $baseUri['scheme']) {
        return $scheme . '://' . $baseUri['host'] . '/' . ltrim($baseUri['path'], '/');
    }

    return $baseUriSpec;
}

$baseUrl = 'example.com';
$path = '/path/to/resource';

$concatUrl = concat($baseUrl, $path);
$sanitizedUrl = sanitizeBaseUrl($baseUrl, $path);
--EXPECT--
