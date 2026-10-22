--TEST--
Translated seed (inferredbugs-csharp: consoleframework/12)
--FILE--
<?php
class TestExtension {
    public string $Property1;
    public string $Property2;
    public string $Property3;
    function __construct(string $param1 = '', string $param2 = '') {
        $this->Property1 = $param1;
        $this->Property2 = $param2;
        $this->Property3 = '';
    }
    function ProvideValue() {
        return $this->Property1 . '_' . $this->Property2 . '_' . $this->Property3;
    }
}

$xaml = '<root><item>{Test a b}</item><item>{}plain</item><item>text</item></root>';
$xml = new SimpleXMLElement($xaml);

$out = [];
foreach ($xml->item as $node) {
    $s = (string)$node;
    if ($s !== '' && $s[0] === '{' && strlen($s) > 1 && $s[1] !== '}') {
        $parts = preg_split('/\s+/', trim(substr($s, 1)), -1, PREG_SPLIT_NO_EMPTY);
        $ext = new TestExtension($parts[1] ?? '', $parts[2] ?? '');
        $out[] = $ext->ProvideValue();
    } elseif (strlen($s) > 1 && $s[0] === '{' && $s[1] === '}') {
        $out[] = substr($s, 2);
    } else {
        $out[] = $s;
    }
}

echo implode("\n", $out);
?>
--EXPECT--
