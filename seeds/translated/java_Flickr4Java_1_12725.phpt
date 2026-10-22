--TEST--
Translated seed (inferredbugs-java: Flickr4Java/1)
--FILE--
<?php

class REST {
    private $proxyAuth = false;
    private $proxyUser = "";
    private $proxyPassword = "";
    private $connectTimeoutMs;
    private $readTimeoutMs;

    public function setProxy($proxyHost, $proxyPort, $username = null, $password = null) {
        $this->proxyAuth = true;
        $this->proxyUser = $username;
        $this->proxyPassword = $password;
    }

    public function get($path, $parameters) {
        $url = "http://example.com" . $path . '?' . http_build_query($parameters);
        $options = [
            'http' => [
                'header' => $this->getProxyAuthHeader(),
                'method' => 'GET',
                'timeout' => $this->readTimeoutMs / 1000,
            ]
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        
        $xml = simplexml_load_string($response);
        return $xml;
    }

    private function getProxyAuthHeader() {
        if ($this->proxyAuth && $this->proxyUser && $this->proxyPassword) {
            $credentials = base64_encode("{$this->proxyUser}:{$this->proxyPassword}");
            return "Proxy-Authorization: Basic $credentials\r\n";
        }
        return '';
    }
}

$rest = new REST();
$rest->setProxy('proxy.example.com', 8080, 'user', 'pass');
$response = $rest->get('/api/method', ['param1' => 'value1', 'param2' => 'value2']);
print_r($response);
--EXPECT--
