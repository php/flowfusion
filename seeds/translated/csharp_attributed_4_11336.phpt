--TEST--
Translated seed (inferredbugs-csharp: attributed/4)
--FILE--
<?php
class LogAsScalarAttribute { public bool $IsMutable; function __construct(bool $m = false) { $this->IsMutable = $m; } }
class NotLoggedAttribute {}
class LogMaskedAttribute {
    public int $ShowFirst;
    public int $ShowLast;
    public bool $PreserveLength;
    public string $Text;
    function __construct(int $sf = 0, int $sl = 0, bool $pl = false, string $text = '*') {
        $this->ShowFirst = $sf; $this->ShowLast = $sl; $this->PreserveLength = $pl; $this->Text = $text;
    }
    function IsDefaultMask(): bool { return $this->ShowFirst === 0 && $this->ShowLast === 0 && $this->Text === '*'; }
}
class ScalarValue { public $value; function __construct($v) { $this->value = $v; } }
class LogEventProperty { public string $name; public $value; function __construct(string $n, $v) { $this->name = $n; $this->value = $v; } }
class StructureValue { public array $properties; public string $typeName; function __construct(array $p, string $t) { $this->properties = $p; $this->typeName = $t; } }

class T {
    #[LogMaskedAttribute(0, 0, true, "#")]
    public string $a = "abcdef";
    #[NotLoggedAttribute]
    public string $b = "skip";
    #[LogAsScalarAttribute(true)]
    public int $c = 123;
}

$o = new T();
$r = [];
foreach ((new ReflectionClass($o))->getProperties() as $p) {
    if ($p->getAttributes(NotLoggedAttribute::class)) continue;
    $v = $p->getValue($o);
    $m = $p->getAttributes(LogMaskedAttribute::class);
    if ($m && is_string($v)) {
        $a = $m[0]->newInstance();
        if ($a->ShowFirst === 0 && $a->ShowLast === 0) {
            $v = $a->PreserveLength ? str_repeat($a->Text[0], strlen($v)) : $a->Text;
        }
    }
    $r[] = new LogEventProperty($p->getName(), new ScalarValue($v));
}
new StructureValue($r, (new ReflectionClass($o))->getShortName());
--EXPECT--
