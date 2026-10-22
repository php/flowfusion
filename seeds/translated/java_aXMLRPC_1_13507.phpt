--TEST--
Translated seed (inferredbugs-java: aXMLRPC/1)
--FILE--
<?php

class XMLRPCClient {
    const DEFAULT_USER_AGENT = "aXMLRPC";
    const CONTENT_TYPE = "Content-Type";
    const TYPE_XML = "text/xml";
    const HTTP_POST = "POST";

    private $url;
    private $httpParameters = [];
    private $flags;

    public function __construct($url, $userAgent = self::DEFAULT_USER_AGENT, $flags = 0) {
        $this->url = $url;
        $this->flags = $flags;
        $this->httpParameters[self::CONTENT_TYPE] = self::TYPE_XML;
        $this->httpParameters["User-Agent"] = $userAgent;
    }

    public function call($method, $params = null) {
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->formatHeaders());
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->createXML($method, $params));
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return $this->parseResponse($response);
    }

    private function formatHeaders() {
        $headers = [];
        foreach ($this->httpParameters as $key => $value) {
            $headers[] = "$key: $value";
        }
        return $headers;
    }

    private function createXML($method, $params) {
        $xml = "<methodCall><methodName>$method</methodName><params>";
        if ($params) {
            foreach ($params as $param) {
                $xml .= "<param><value>" . htmlspecialchars($param) . "</value></param>";
            }
        }
        $xml .= "</params></methodCall>";
        return $xml;
    }

    private function parseResponse($response) {
        return simplexml_load_string($response);
    }
}

$url = "http://example.com/xmlrpc";
$client = new XMLRPCClient($url);
$response = $client->call("example.method", ["param1", "param2"]);
--EXPECT--
