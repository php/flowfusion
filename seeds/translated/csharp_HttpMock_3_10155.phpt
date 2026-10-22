--TEST--
Translated seed (inferredbugs-csharp: HttpMock/3)
--FILE--
<?php
class HttpServer {
    public bool $available = true;
    public string $uri;
    public function __construct(string $uri) { $this->uri = $uri; }
    public function start(): void { $this->available = true; }
    public function isAvailable(): bool { return $this->available; }
}

class HttpServerFactory {
    private array $httpServers = [];

    public function get(string $uri): HttpServer {
        $port = (int)(parse_url($uri, PHP_URL_PORT) ?? 0);
        if (array_key_exists($port, $this->httpServers)) {
            $httpServer = $this->httpServers[$port];
            if ($httpServer->isAvailable()) return $httpServer;
        }
        return $this->create($uri);
    }

    public function create(string $uri): HttpServer {
        $port = (int)(parse_url($uri, PHP_URL_PORT) ?? 0);
        $httpServer = $this->buildServer($uri);
        $this->httpServers[$port] = $httpServer;
        return $this->httpServers[$port];
    }

    private function buildServer(string $uri): HttpServer {
        $httpServer = new HttpServer($uri);
        $httpServer->start();
        return $httpServer;
    }
}

$factory = new HttpServerFactory();
$u1 = 'http://example.com:8080/';
$u2 = 'http://example.com:8080/path';
$u3 = 'http://example.com:8081/';
$a = $factory->get($u1);
$b = $factory->get($u2);
$c = $factory->get($u3);
$a->available = false;
$d = $factory->get($u1);
echo ($a === $b ? "1" : "0") . ($c !== $a ? "1" : "0") . ($d !== $a ? "1" : "0");
?>
--EXPECT--
