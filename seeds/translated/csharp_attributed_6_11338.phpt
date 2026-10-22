--TEST--
Translated seed (inferredbugs-csharp: attributed/6)
--FILE--
<?php
class LogAsScalarAttribute { public bool $IsMutable; function __construct(bool $IsMutable = false) { $this->IsMutable = $IsMutable; } }
class NotLoggedAttribute {}
class LogMaskedAttribute {
    public int $ShowFirst;
    public int $ShowLast;
    public bool $PreserveLength;
    public string $Text;
    function __construct(int $ShowFirst = 0, int $ShowLast = 0, bool $PreserveLength = false, string $Text = '*') {
        $this->ShowFirst = $ShowFirst; $this->ShowLast = $ShowLast; $this->PreserveLength = $PreserveLength; $this->Text = $Text;
    }
    function IsDefaultMask(): bool { return $this->Text === '*'; }
}

class T {
    #[LogMaskedAttribute(2, 0, true, '*')]
    public string $a = "abcdef";
    #[NotLoggedAttribute]
    public string $b = "skip";
    #[LogAsScalarAttribute(true)]
    public string $c = "scalar";
}

$o = new T();
$r = [];
$rc = new ReflectionClass($o);
foreach ($rc->getProperties() as $p) {
    if ($p->getAttributes(NotLoggedAttribute::class)) continue;
    $v = $p->getValue($o);
    $mask = $p->getAttributes(LogMaskedAttribute::class);
    if ($mask && is_string($v)) {
        $m = $mask[0]->newInstance();
        if ($m->ShowFirst === 0 && $m->ShowLast === 0) {
            $v = $m->PreserveLength ? str_repeat($m->Text[0], strlen($v)) : $m->Text;
        } elseif ($m->ShowFirst > 0 && $m->ShowLast === 0) {
            $first = substr($v, 0, $m->ShowFirst);
            $v = $m->PreserveLength && $m->IsDefaultMask() ? $first . str_repeat($m->Text[0], strlen($v) - $m->ShowFirst) : $first . $m->Text;
        } elseif ($m->ShowFirst === 0 && $m->ShowLast > 0) {
            $last = substr($v, -$m->ShowLast);
            $v = $m->PreserveLength && $m->IsDefaultMask() ? str_repeat($m->Text[0], strlen($v) - $m->ShowLast) . $last : $m->Text . $last;
        } elseif ($m->ShowFirst > 0 && $m->ShowLast > 0) {
            $v = substr($v, 0, $m->ShowFirst) . $m->Text . substr($v, -$m->ShowLast);
        }
    }
    $r[$p->getName()] = $v;
}
echo json_encode($r, JSON_UNESCAPED_SLASHES), PHP_EOL;
--EXPECT--
