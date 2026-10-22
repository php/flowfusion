--TEST--
Translated seed (inferredbugs-csharp: Prism.Container.Extensions/2)
--FILE--
<?php
class PrismContainerExtension {
    private static ?self $current = null;
    private ?array $currentScope = null;
    public array $instance = [];

    public static function current(): self {
        if (self::$current === null) self::create();
        return self::$current;
    }

    public static function reset(): void {
        self::$current = null;
    }

    public static function create(): self {
        if (self::$current !== null) throw new RuntimeException("already created");
        return new self();
    }

    private function __construct() {
        self::$current = $this;
        $this->instance['container'] = $this;
        $this->instance['provider'] = $this;
        $this->instance['registry'] = $this;
        $this->instance['service_provider'] = $this;
        $this->instance['scope_factory'] = $this;
    }

    public function createScope(): void {
        if ($this->currentScope !== null) $this->currentScope = null;
        $this->currentScope = ['container' => []];
    }

    public function resolve(string $type, array $parameters = []) {
        $c = $this->currentScope['container'] ?? $this->instance;
        return $c[$type] ?? null;
    }

    public function getService(string $type) {
        return $this->resolve($type);
    }
}

$ext = PrismContainerExtension::create();
$ext->createScope();
$ext->createScope();
PrismContainerExtension::reset();
$ext2 = PrismContainerExtension::current();
$ext2->getService('missing');
?>
--EXPECT--
