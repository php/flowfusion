--TEST--
Translated seed (inferredbugs-java: boilerpipe/3)
--FILE--
<?php
class HTMLFetcher {
    private function __construct() {}

    public static function fetch($url) {
        $headers = get_headers($url, 1);
        $charset = isset($headers['Content-Type']) ? preg_match('/charset=([^;]+)/', $headers['Content-Type'], $match) ? $match[1] : 'Cp1252' : 'Cp1252';

        $options = [
            "http" => [
                "header" => "Accept-Encoding: gzip\r\n"
            ]
        ];
        $context = stream_context_create($options);
        $data = file_get_contents($url, false, $context);
        
        if (stripos($headers['Content-Encoding'], 'gzip') !== false) {
            $data = gzinflate(substr($data, 10));
        } elseif (strpos($headers['Content-Encoding'], 'gzip') !== false) {
            echo "WARN: unsupported Content-Encoding: " . $headers['Content-Encoding'];
        }
        
        return new HTMLDocument($data, $charset);
    }
}

class HTMLDocument {
    public function __construct($data, $charset) {
        // Implementation details of HTMLDocument
    }
}

HTMLFetcher::fetch('http://example.com');
--EXPECT--
