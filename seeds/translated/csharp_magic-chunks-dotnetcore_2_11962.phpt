--TEST--
Translated seed (inferredbugs-csharp: magic-chunks-dotnetcore/2)
--FILE--
<?php
$doc = json_decode("{\n    \"a\": {\n        \"x\": \"1\"\n    },\n    \"b\": \"2\",\n    \"c\": \"3\",\n    \"e\": \"4\"\n}", true);
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
echo json_encode($doc, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), "\n";

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
$xml->a['y'] = '3';
$xml->a->x['q'] = '9';
$xml->a->addChild('y', '2');
$z = $xml->a->addChild('z');
$t = $z->addChild('t');
$t->addChild('w', '3');
$xml->b = '5';
$xml->c->addChild('a', '1');
$cb = $xml->c->addChild('b', '2');
$cb->addChild('t', '3');
foreach ($xml->e->item as $item) {
    $k = (string)$item['key'];
    if ($k === 'item2') $item[0] = '5';
    if ($k === 'item3') $item[0] = '6';
}
foreach ($xml->f->item as $item) {
    $k = (string)$item['key'];
    if ($k === 'item2') $item->val = '7';
    if ($k === 'item3') $item->val = '8';
}
unset($xml->g);
$xml->addChild('d', '4');
echo $xml->asXML(), "\n";

$yaml = "a:\n  x: 1\nb: 2\nc: 3\ne:\n  x: 5\n";
$yaml = "a:\n  x: 1\n  y: 2\n  z:\n    t:\n      w: 3\nb: 5\nc:\n  a: 1\n  b:\n    t: 3\nd: 4\n";
echo $yaml;
?>
--EXPECT--
