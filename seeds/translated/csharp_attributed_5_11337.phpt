--TEST--
Translated seed (inferredbugs-csharp: attributed/5)
--FILE--
<?php
class LogAsScalarAttribute { public bool $IsMutable; function __construct(bool $m = false) { $this->IsMutable = $m; } }
class NotLoggedAttribute {}
class LogMaskedAttribute {
    public int $ShowFirst;
    public int $ShowLast;
    public bool $PreserveLength;
    public string $Text;
    function __construct(int $sf = 0, int $sl = 0, bool $pl = false, string $text = "*") {
        $this->ShowFirst = $sf; $this->ShowLast = $sl; $this->PreserveLength = $pl; $this->Text = $text;
    }
    function IsDefaultMask(): bool { return $this->Text === "*"; }
}

class Example {
    public string $a = "secret";
    public string $b = "keep";
    public string $c = "tail";
}

$rc = new ReflectionClass(Example::class);
foreach ($rc->getProperties() as $p) {
    if ($p->getName() === 'a') $p->setValue(new Example(), 'secret');
}

$obj = new Example();
$rp = new ReflectionProperty(Example::class, 'a');
$val = $rp->getValue($obj);

$attr = new LogMaskedAttribute(0, 0, true, "#");
if (is_string($val)) {
    if ($attr->ShowFirst == 0 && $attr->ShowLast == 0) {
        $val = $attr->PreserveLength ? str_repeat($attr->Text[0], strlen($val)) : $attr->Text;
    } elseif ($attr->ShowFirst > 0 && $attr->ShowLast == 0) {
        $first = substr($val, 0, $attr->ShowFirst);
        $val = $attr->PreserveLength && $attr->IsDefaultMask() ? $first . str_repeat($attr->Text[0], strlen($val) - $attr->ShowFirst) : $first . $attr->Text;
    } elseif ($attr->ShowFirst == 0 && $attr->ShowLast > 0) {
        $last = substr($val, strlen($val) - $attr->ShowLast);
        $val = $attr->PreserveLength && $attr->IsDefaultMask() ? str_repeat($attr->Text[0], strlen($val) - $attr->ShowLast) . $last : $attr->Text . $last;
    } else {
        $first = substr($val, 0, $attr->ShowFirst);
        $last = substr($val, strlen($val) - $attr->ShowLast);
        $val = $first . $attr->Text . $last;
    }
}

echo $val;
?>
--EXPECT--
