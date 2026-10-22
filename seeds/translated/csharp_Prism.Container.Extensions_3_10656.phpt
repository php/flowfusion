--TEST--
Translated seed (inferredbugs-csharp: Prism.Container.Extensions/3)
--FILE--
<?php
class PrismContainerExtension
{
    private static ?self $current = null;
    private ?array $scope = null;
    public array $instance = [];

    public static function create(): self
    {
        if (self::$current !== null) {
            throw new RuntimeException('already created');
        }
        return new self();
    }

    public static function current(): self
    {
        if (self::$current === null) {
            self::create();
        }
        return self::$current;
    }

    public static function reset(): void
    {
        self::$current = null;
        gc_collect_cycles();
    }

    private function __construct()
    {
        self::$current = $this;
    }

    public function createScope(): void
    {
        if ($this->scope !== null) {
            $this->scope = null;
            gc_collect_cycles();
        }
        $this->scope = [];
    }

    public function register(string $type, $value): void
    {
        $this->instance[$type] = $value;
    }

    public function resolve(string $type)
    {
        $c = $this->scope ?? $this->instance;
        return $c[$type] ?? null;
    }
}

PrismContainerExtension::reset();
$a = PrismContainerExtension::create();
$a->register('x', 1);
$a->createScope();
$a->register('y', 2);
$a->createScope();
$b = PrismContainerExtension::current();
$b->resolve('x');
PrismContainerExtension::reset();
$c = PrismContainerExtension::create();
$c->register('z', 3);
$c->resolve('z');
?>
--EXPECT--
