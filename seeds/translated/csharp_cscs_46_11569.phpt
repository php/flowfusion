--TEST--
Translated seed (inferredbugs-csharp: cscs/46)
--FILE--
<?php
$e = new class {
    public $v = [];
    function __construct() {
        $this->v = [1, 2, 3];
    }
    function add(&$a, $b) {
        $a[] = $b;
        return $a;
    }
    function removeAt(&$a, $i) {
        array_splice($a, $i, 1);
        return $a;
    }
};

$a = [0 => ["x" => 1], 1 => ["x" => 2]];
$b = $a;
$b[0]["x"]++;
$b[1]["y"] = "3";
$c = $e->add($a, ["z" => 4]);
$d = $e->removeAt($c, 1);

$map = [];
foreach (["a\t1", "b\t2", "a\t3"] as $line) {
    [$k, $v] = explode("\t", $line, 2);
    $map[$k][] = $v;
}

$nested = [];
$nested["k"]["m"] = 7;
$nested["k"]["n"] = $nested["k"]["m"] + 1;

$x = "5";
$y = (int)$x + 1;
$z = (string)$y . "0";

$props = array_keys($nested["k"]);
sort($props);
echo json_encode([
    "a" => $a,
    "b" => $b,
    "c" => $c,
    "d" => $d,
    "map" => $map,
    "nested" => $nested,
    "z" => $z,
    "props" => $props
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), PHP_EOL;
?>
--EXPECT--
