--TEST--
Translated seed (inferredbugs-csharp: pnpframework/1)
--FILE--
<?php
class PnPContext {
    public string $uri;
    public function __construct(string $uri) { $this->uri = $uri; }
    public function __clone() {}
}

class ClientContext {
    public string $url;
    public function __construct(string $url) { $this->url = $url; }
}

class PnPCoreSdk {
    private static ?self $instance = null;
    private array $cache = [];

    public static function instance(): self {
        return self::$instance ??= new self();
    }

    public function getPnPContext(ClientContext $context): PnPContext {
        $u = parse_url($context->url);
        $scheme = $u['scheme'] ?? 'https';
        $host = strtolower($u['host'] ?? '');
        $port = isset($u['port']) ? ':' . $u['port'] : '';
        $authority = $host . $port;
        $ctxUri = $scheme . '://' . $authority . ($u['path'] ?? '');
        if (isset($this->cache[$authority])) {
            return $this->cache[$authority]->uri === $ctxUri ? $this->cache[$authority] : clone $this->cache[$authority];
        }
        return new PnPContext($ctxUri);
    }

    public function getClientContext(PnPContext $pnpContext): ClientContext {
        $authority = strtolower(parse_url($pnpContext->uri, PHP_URL_HOST) ?? '');
        if (!isset($this->cache[$authority])) {
            $this->cache[$authority] = $pnpContext;
        }
        return new ClientContext($pnpContext->uri);
    }
}

$sdk = PnPCoreSdk::instance();
$c1 = new ClientContext('https://Example.COM/sites/a');
$p1 = $sdk->getPnPContext($c1);
$c2 = $sdk->getClientContext($p1);
$p2 = $sdk->getPnPContext($c2);
$c3 = new ClientContext('https://example.com/sites/b');
$p3 = $sdk->getPnPContext($c3);
echo $p1->uri . "\n" . $p2->uri . "\n" . $p3->uri . "\n";
--EXPECT--
