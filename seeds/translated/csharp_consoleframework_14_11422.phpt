--TEST--
Translated seed (inferredbugs-csharp: consoleframework/14)
--FILE--
<?php
class TestExtension {
    public string $Property1;
    public string $Property2;
    public string $Property3;
    public function __construct(string $param1 = '', string $param2 = '') {
        $this->Property1 = $param1;
        $this->Property2 = $param2;
        $this->Property3 = '';
    }
    public function ProvideValue($context) {
        return $this->Property1 . '_' . $this->Property2 . '_' . $this->Property3;
    }
}

class Node {
    public string $Content = '';
    public string $Title = '';
    public bool $Enabled = false;
    public int $Count = 0;
    public array $Items = [];
}

$xaml = '<Node Title="{Test A,B}" Enabled="true" Count="2147483647"><Node.Content>{}literal</Node.Content><Node.Items><item>1</item><item>{Test X,Y}</item></Node.Items></Node>';

$xml = new SimpleXMLElement($xaml);
$root = new Node();
$root->Title = (string)$xml['Title'];
$root->Enabled = filter_var((string)$xml['Enabled'], FILTER_VALIDATE_BOOLEAN);
$root->Count = (int)$xml['Count'];
$root->Content = (string)$xml->{'Node.Content'};
foreach ($xml->Node->item as $item) {
    $root->Items[] = (string)$item;
}
echo $root->Title . "\n";
echo ($root->Enabled ? '1' : '0') . "\n";
echo $root->Count . "\n";
echo $root->Content . "\n";
echo implode(',', $root->Items) . "\n";
?>
--EXPECT--
