--TEST--
Translated seed (inferredbugs-csharp: Hyprlinkr/1)
--FILE--
<?php
class Request {
    public string $requestUri;
    public array $properties;
    public function __construct(string $requestUri, array $properties = []) {
        $this->requestUri = $requestUri;
        $this->properties = $properties;
    }
}
class RouteLinker {
    private Request $request;
    public function __construct(Request $request) {
        $this->request = $request;
    }
    public function getUri(callable $method): string {
        $rf = new ReflectionFunction(Closure::fromCallable($method));
        $args = [];
        foreach ($rf->getParameters() as $p) {
            $args[] = (string)($p->isDefaultValueAvailable() ? $p->getDefaultValue() : null);
        }
        $path = $method(...$args);
        $base = preg_replace('~[?#].*$~', '', $this->request->requestUri);
        $base = preg_replace('~/$~', '', $base);
        $path = ltrim((string)$path, '/');
        return $base . '/' . $path;
    }
}
$request = new Request('http://localhost/api');
$linker = new RouteLinker($request);
echo $linker->getUri(fn($id) => 'foo/' . (string)$id), PHP_EOL;
echo $linker->getUri(fn($a, $b = 'x') => 'bar/' . $a . '/' . $b), PHP_EOL;
echo $linker->getUri(fn($s) => 'baz/' . rawurlencode((string)$s)), PHP_EOL;
?>
--EXPECT--
