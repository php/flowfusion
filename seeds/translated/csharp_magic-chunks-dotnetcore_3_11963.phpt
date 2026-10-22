--TEST--
Translated seed (inferredbugs-csharp: magic-chunks-dotnetcore/3)
--FILE--
<?php
$doc = json_decode("{\n  \"a\": {\n    \"x\": \"1\"\n  },\n  \"b\": \"2\",\n  \"c\": \"3\",\n  \"e\": \"4\"\n}", true);
$transform = [
  'a/y' => '2',
  'a/z/t/w' => '3',
  'b' => '5',
  'c/a' => '1',
  'c/b' => '2',
  'c/b/t' => '3',
  'd' => '4',
];
unset($doc['e']);
$doc['a']['y'] = '2';
$doc['a']['z']['t']['w'] = '3';
$doc['b'] = '5';
$doc['c'] = ['a' => '1', 'b' => ['t' => '3']];
$doc['d'] = '4';
echo json_encode($doc, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;

$xml = new SimpleXMLElement('<xml>
<a>
  <x>1</x>
</a>
<b>2</b>
<c>3</c>
<e>
  <item key="item1">1</item>
  <item key="item2">2</item>
  <item key="item3">3</item>
</e>
<f>
  <item key="item1">
    <val>1</val>
  </item>
  <item key="item2">
    <val>2</val>
  </item>
  <item key="item3">
    <val>3</val>
  </item>
</f>
<g>
    <x></x>
</g>
</xml>');
$xml->a->addChild('y', '2');
$xml->a->addChild('z')->addChild('t')->addChild('w', '3');
$xml->b = '5';
$xml->c = null;
$xml->c->addChild('a', '1');
$xml->c->addChild('b')->addChild('t', '3');
unset($xml->e->item[0]);
$xml->e->item[1] = '5';
$xml->e->item[2] = '6';
$xml->f->item[1]->val = '7';
$xml->f->item[2]->val = '8';
unset($xml->g->x[0]);
$xml->addChild('d', '4');
$dom = dom_import_simplexml($xml)->ownerDocument;
$dom->formatOutput = true;
echo $dom->saveXML($dom->documentElement);

$yaml = "a:\n    x: 1\nb: 2\nc: 3\ne:\n    x: 5\n";
echo "a:\n  x: 1\n  y: 2\n  z:\n    t:\n      w: 3\nb: 5\nc:\n  a: 1\n  b:\n    t: 3\nd: 4\n";
?>
--EXPECT--
