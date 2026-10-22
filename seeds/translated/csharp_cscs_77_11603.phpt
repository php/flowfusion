--TEST--
Translated seed (inferredbugs-csharp: cscs/77)
--FILE--
<?php
function show($v) { var_dump($v); }

$a = [];
$a["x"] = 1;
$a["x"] += 2;
$a["y"] = ["k" => 3];
$a["y"]["k"]++;
$a["z"] = "9";
$a["z"] .= 1;
$a["w"] = null;
$a["w"] ??= "fallback";

$keys = array_keys($a);
sort($keys);
show($keys);
show($a);

$nested = [];
$nested[0][1][2] = "deep";
$nested[0][1][2] = $nested[0][1][2] . "copy";
show($nested);

$s = "abc";
$s[1] = "Z";
show($s);

$i = 0;
while ($i < 3) {
    if ($i === 1) {
        $i++;
        continue;
    }
    if ($i === 2) {
        break;
    }
    $i++;
}

try {
    throw new Exception((string)$a["z"]);
} catch (Throwable $e) {
    show($e->getMessage());
}

class C {
    public $p = 1;
    function __toString() { return "C:" . $this->p; }
}
$o = new C();
$o->p += 4;
show((string)$o);

echo "ok\n";
?>
--EXPECT--
