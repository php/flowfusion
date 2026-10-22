--TEST--
Translated seed (inferredbugs-csharp: consoleframework/6)
--FILE--
<?php
class Window { public $Title = ''; public $Content; }
class GroupBox { public $Header = ''; public $Content; }
class ScrollViewer { public $Content; }
class ListBox { public $Items; public function __construct(){ $this->Items = []; } }

$xaml = '<Window Title="Main"><GroupBox Header="H"><ListBox><ListBox.Items><item>A</item><item>B</item></ListBox.Items></ListBox></GroupBox></Window>';

$xml = new SimpleXMLElement($xaml);
$root = new Window();
$root->Title = (string)$xml['Title'];

$gb = new GroupBox();
$gb->Header = (string)$xml->GroupBox['Header'];

$lb = new ListBox();
foreach ($xml->GroupBox->ListBox->children() as $child) {
    if ($child->getName() === 'item') $lb->Items[] = (string)$child;
}
$gb->Content = $lb;
$root->Content = $gb;

echo $root->Title . "\n";
echo $root->Content->Header . "\n";
echo implode(',', $root->Content->Content->Items) . "\n";
?>
--EXPECT--
