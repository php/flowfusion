--TEST--
Translated seed (inferredbugs-csharp: RedditSharp/2)
--FILE--
<?php
class RedditAPINameAttribute {
    public $Name;
    function __construct($name) { $this->Name = $name; }
}

class T {
    #[RedditAPINameAttribute("x")]
    private $a = "1&2;3";
    public $b = null;
}

$d = new T();
$o = new ReflectionObject($d);
$p = [];
foreach ($o->getProperties() as $r) {
    $r->setAccessible(true);
    $a = $r->getAttributes(RedditAPINameAttribute::class);
    $n = $a ? $a[0]->newInstance()->Name : $r->getName();
    $v = $r->getValue($d);
    $p[] = $n . "=" . rawurlencode((string)$v);
}
$p[] = "c=" . rawurlencode((string)"");
$s = implode("&", $p);
$h = fopen("php://temp", "w+");
fwrite($h, $s);
rewind($h);
echo stream_get_contents($h);
fclose($h);
?>
--EXPECT--
