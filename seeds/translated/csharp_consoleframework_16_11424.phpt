--TEST--
Translated seed (inferredbugs-csharp: consoleframework/16)
--FILE--
<?php
class TestExtension {
    public string $Property1 = '';
    public string $Property2 = '';
    public string $Property3 = '';
    public function __construct(string $p1 = '', string $p2 = '') { $this->Property1 = $p1; $this->Property2 = $p2; }
    public function provideValue(): string { return $this->Property1 . '_' . $this->Property2 . '_' . $this->Property3; }
}

class Holder {
    public array $Items = [];
    public array $Map = [];
    public string $Content = '';
    public int $Number = 0;
    public bool $Flag = false;
}

$xml = '<Holder xmlns:x="http://consoleframework.org/xaml.xsd" Number="42" Flag="true"><Items><item>{}plain</item><item>{Test a,b}</item></Items><Map><item x:Key="k1">7</item></Map><Content>{Test x,y}</Content></Holder>';

$root = new SimpleXMLElement($xml);
$obj = new Holder();
$obj->Number = (int)$root['Number'];
$obj->Flag = filter_var((string)$root['Flag'], FILTER_VALIDATE_BOOL);

foreach ($root->Items->item as $it) {
    $s = (string)$it;
    $obj->Items[] = str_starts_with($s, '{}') ? substr($s, 2) : (str_starts_with($s, '{Test ') ? (new TestExtension('a', 'b'))->provideValue() : $s);
}

foreach ($root->Map->item as $it) {
    $obj->Map[(string)$it['x:Key']] = (int)$it;
}

$s = (string)$root->Content;
$obj->Content = str_starts_with($s, '{Test ') ? (new TestExtension('x', 'y'))->provideValue() : $s;

echo $obj->Content . "\n";
echo $obj->Items[0] . "\n";
echo $obj->Items[1] . "\n";
echo $obj->Map['k1'] . "\n";
echo $obj->Number . "\n";
echo ($obj->Flag ? '1' : '0') . "\n";
?>
--EXPECT--
