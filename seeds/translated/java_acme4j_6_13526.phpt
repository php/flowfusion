--TEST--
Translated seed (inferredbugs-java: acme4j/6)
--FILE--
<?php

class Session {
    private $resourceMap = [];
    private $serverUri;
    private $keyPair;
    private $nonce;
    private $directoryJson;
    private $metadata;
    private $locale;
    private $directoryCacheExpiry;

    public function __construct($serverUri, $keyPair) {
        $this->serverUri = $serverUri;
        $this->keyPair = $keyPair;
        $this->locale = 'en_US';
    }

    public function getServerUri() {
        return $this->serverUri;
    }

    public function getKeyPair() {
        return $this->keyPair;
    }

    public function setNonce($nonce) {
        $this->nonce = $nonce;
    }

    public function getNonce() {
        return $this->nonce;
    }

    public function readDirectory() {
        $now = new DateTime();
        if ($this->directoryJson === null || $this->directoryCacheExpiry < $now) {
            $this->directoryJson = json_decode('{"meta":{}}');
            $this->directoryCacheExpiry = (clone $now)->modify('+1 hour');

            $this->metadata = isset($this->directoryJson->meta) ? $this->directoryJson->meta : new stdClass();
            $this->resourceMap = [
                'example' => 'http://example.com/resource'
            ];
        }
    }

    public function resourceUri($resource) {
        $this->readDirectory();
        return $this->resourceMap[$resource] ?? null;
    }
}

$session = new Session('http://example.com', 'dummy_keypair');
$uri = $session->resourceUri('example');
echo $uri;
--EXPECT--
