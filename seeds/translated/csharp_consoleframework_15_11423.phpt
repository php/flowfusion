--TEST--
Translated seed (inferredbugs-csharp: consoleframework/15)
--FILE--
<?php
class TestExtension {
    public string $Property1;
    public string $Property2;
    public string $Property3;
    function __construct(string $p1 = '', string $p2 = '', string $p3 = '') {
        $this->Property1 = $p1;
        $this->Property2 = $p2;
        $this->Property3 = $p3;
    }
    function provideValue(): string {
        return $this->Property1 . '_' . $this->Property2 . '_' . $this->Property3;
    }
}

class Node {
    public ?Node $Content = null;
    public array $Items = [];
    public string $Text = '';
    public bool $Flag = false;
    public int $Count = 0;
    public string $Mode = '';
}

function processText(string $text) {
    if ($text === '') return '';
    if ($text[0] !== '{') return $text;
    if (strlen($text) > 1 && $text[1] === '}') return strlen($text) > 2 ? substr($text, 2) : '';
    if (preg_match('/^\{Test(?:\s+([^,}]+))?(?:,\s*([^,}]+))?(?:,\s*([^,}]+))?\}$/', $text, $m)) {
        $e = new TestExtension($m[1] ?? '', $m[2] ?? '', $m[3] ?? '');
        return $e->provideValue();
    }
    return $text;
}

$xaml = '<Node Flag="true" Count="7" Mode="{Test A,B,C}"><Content>{}literal</Content><Items><Node Count="1"/><Node Count="2"/></Items></Node>';
$xml = new SimpleXMLElement($xaml);
$root = new Node();
$root->Flag = filter_var((string)$xml['Flag'], FILTER_VALIDATE_BOOLEAN);
$root->Count = (int)(string)$xml['Count'];
$root->Mode = (string)processText((string)$xml['Mode']);

$root->Content = new Node();
$root->Content->Text = (string)processText((string)$xml->Content);

foreach ($xml->Items->Node as $n) {
    $child = new Node();
    $child->Count = (int)(string)$n['Count'];
    $root->Items[] = $child;
}

echo $root->Mode . "\n";
echo $root->Content->Text . "\n";
echo count($root->Items) . "\n";
?>
--EXPECT--
