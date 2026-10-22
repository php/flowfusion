--TEST--
Translated seed (inferredbugs-csharp: cscs/58)
--FILE--
<?php
$e = new class {
    public array $t = [];
    public function __toString(): string { return "ok"; }
};
$a = [];
$a["x"] = &$e;
$b = $a;
$b["x"]->t[] = 1;
$b["x"]->t[] = 2;
$c = $b;
$c["x"]->t[1] = 3;
$d = [];
$d["k"]["m"] = $c["x"]->t;
$d["k"]["n"] = $d["k"]["m"];
$d["k"]["n"][] = 4;
echo count($a["x"]->t) . "\n";
echo $b["x"]->t[1] . "\n";
echo $c["x"]->t[2] . "\n";
echo $d["k"]["n"][3] . "\n";
?>
--EXPECT--
