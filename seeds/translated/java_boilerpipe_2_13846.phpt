--TEST--
Translated seed (inferredbugs-java: boilerpipe/2)
--FILE--
<?php

class HTMLFetcher {
    private function __construct() {}

    public static function fetch($url) {
        $headers = get_headers($url, 1);
        $charset = "Cp1252";

        if (isset($headers['Content-Encoding'])) {
            $encoding = $headers['Content-Encoding'];
            if (strtolower($encoding) === 'gzip') {
                $context = stream_context_create(['http' => ['header' => 'Accept-Encoding: gzip']]);
                $data = file_get_contents($url, false, $context);
                $data = gzinflate(substr($data, 10, -8));
            } else {
                echo "WARN: unsupported Content-Encoding: $encoding\n";
                $data = file_get_contents($url);
            }
        } else {
            $data = file_get_contents($url);
        }

        return new HTMLDocument($data, $charset);
    }
}

class HTMLDocument {
    public function __construct($data, $charset) {
        // Constructor logic
    }
}

HTMLFetcher::fetch("http://example.com");
--EXPECT--
