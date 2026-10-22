--TEST--
Translated seed (inferredbugs-csharp: OPC-UA-OOI/2)
--FILE--
<?php
interface INamespaces { public function GetNamespaces(): array; }
interface IStylesheetNameProvider { public function getStylesheetName(): ?string; }

class Data implements INamespaces, IStylesheetNameProvider {
    public string $value;
    public function __construct(string $value, private ?string $stylesheetName = null) { $this->value = $value; }
    public function GetNamespaces(): array { return ['x' => 'urn:test']; }
    public function getStylesheetName(): ?string { return $this->stylesheetName; }
}

$d = new Data('hello', 'style.xsl');
$path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'xmlfile_' . uniqid('', true) . '.xml';
$ns = $d->GetNamespaces();
$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";
if (($s = $d->getStylesheetName()) !== null && $s !== '') $xml .= "<?xml-stylesheet type=\"text/xsl\" href=\"" . htmlspecialchars($s, ENT_QUOTES | ENT_XML1) . "\"?>\r\n";
$xml .= "<x:Data xmlns:x=\"" . htmlspecialchars($ns['x'], ENT_QUOTES | ENT_XML1) . "\">\r\n";
$xml .= "  <x:value>" . htmlspecialchars($d->value, ENT_QUOTES | ENT_XML1) . "</x:value>\r\n";
$xml .= "</x:Data>\r\n";
file_put_contents($path, $xml);
$s = file_get_contents($path);
$parsed = simplexml_load_string($s);
unlink($path);
if ($parsed === false) exit(1);
?>
--EXPECT--
