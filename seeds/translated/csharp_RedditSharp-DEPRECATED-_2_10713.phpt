--TEST--
Translated seed (inferredbugs-csharp: RedditSharp-DEPRECATED-/2)
--FILE--
<?php
class RedditAPINameAttribute {
    public string $Name;
    public function __construct(string $name) { $this->Name = $name; }
}

class X {
    #[RedditAPINameAttribute("a")]
    public $p = "1&2;3";
    private $q = null;
}

$o = new X();
$rc = new ReflectionClass($o);
$parts = [];
foreach ($rc->getProperties() as $prop) {
    $prop->setAccessible(true);
    $attrs = $prop->getAttributes(RedditAPINameAttribute::class);
    $name = $attrs ? $attrs[0]->newInstance()->Name : $prop->getName();
    $v = (string)$prop->getValue($o);
    $parts[] = $name . '=' . str_replace([';', '&'], ['%3B', '%26'], rawurlencode($v));
}
$parts[] = 'z=' . str_replace([';', '&'], ['%3B', '%26'], rawurlencode((string)null));
$data = implode('&', $parts);
$stream = fopen('php://temp', 'r+');
fwrite($stream, $data);
rewind($stream);
echo stream_get_contents($stream);
fclose($stream);
--EXPECT--
