--TEST--
Translated seed (inferredbugs-csharp: Shielded/4)
--FILE--
<?php
class ProxyGen {
    private static array $proxies = [];

    public static function getFor(string $t): string {
        return self::$proxies[$t] ??= self::createFor($t);
    }

    private static function createFor(string $t): string {
        $proxy = '__shielded' . (new ReflectionClass($t))->getShortName();
        $struct = '__struct' . (new ReflectionClass($t))->getShortName();
        if (!class_exists($proxy, false)) {
            eval('class ' . $proxy . ' extends ' . $t . ' { public ' . $struct . ' $_data; public function __construct(){ $this->_data = new ' . $struct . '(); } }');
        }
        return $proxy;
    }
}

class BaseType {
    public int $x = 1;
    public function getX(): int { return $this->x; }
    public function setX(int $v): void { $this->x = $v; }
    public function commute(callable $a): void { $a(); }
}

class DerivedType extends BaseType {
    public function __construct() {}
    public function getX(): int { return parent::getX(); }
    public function setX(int $v): void { parent::setX($v); }
}

$proxy = ProxyGen::getFor(DerivedType::class);
$o = new $proxy();
$o->setX(2);
$o->commute(function () use ($o) { $o->setX($o->getX() + 1); });
echo $o->getX(), PHP_EOL;
--EXPECT--
