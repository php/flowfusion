--TEST--
Translated seed (inferredbugs-csharp: dxf/1)
--FILE--
<?php
$xml = <<<XML
<Root xmlns="urn:test">
  <Table>
    <TableItem>
      <Property><Name>Foo</Name><Type>int</Type></Property>
      <Property><Name>Foo</Name><Type>int</Type></Property>
      <Property><Name>Bar</Name><Type>string</Type><AllowMultiples>true</AllowMultiples></Property>
      <Property><Name>Baz</Name><Type>string</Type><Code>-1</Code><CodeOverrides><Code>10</Code><Code>20</Code><Code>30</Code></CodeOverrides></Property>
    </TableItem>
  </Table>
  <Table>
    <TableItem>
      <Property><Name>Qux</Name><Type>float</Type><MinVersion>R13</MinVersion><MaxVersion>R13</MaxVersion></Property>
    </TableItem>
  </Table>
</Root>
XML;

$root = new SimpleXMLElement($xml);
$root->registerXPathNamespace('x', 'urn:test');
$tables = $root->xpath('/x:Root/x:Table');

foreach ($tables as $table) {
    $items = $table->xpath('x:TableItem');
    foreach ($items as $item) {
        $seen = [];
        $props = $item->xpath('x:Property');
        foreach ($props as $p) {
            $name = (string)$p->Name;
            if (!isset($seen[$name])) {
                $seen[$name] = true;
                $type = (string)$p->Type;
                $allowMultiples = ((string)$p->AllowMultiples) === 'true';
                $code = (int)$p->Code;
                $codeOverrides = [];
                if (isset($p->CodeOverrides)) {
                    foreach ($p->CodeOverrides->Code as $c) $codeOverrides[] = (int)$c;
                }
                $minVersion = (string)$p->MinVersion;
                $maxVersion = (string)$p->MaxVersion;
                $predicate = [];
                if ($allowMultiples) $predicate[] = 'multiples';
                if ($code < 0 && $codeOverrides) $predicate[] = 'overrides:' . count($codeOverrides);
                if ($minVersion !== '' && $maxVersion !== '' && $minVersion === $maxVersion) $predicate[] = 'version==' . $minVersion;
                echo $name . ':' . $type . ':' . implode(',', $predicate) . PHP_EOL;
            }
        }
    }
}
?>
--EXPECT--
