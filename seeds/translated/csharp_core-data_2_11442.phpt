--TEST--
Translated seed (inferredbugs-csharp: core-data/2)
--FILE--
<?php
$k = "db";
$a = [];
$a[$k] = "x";
$a[$k] .= "y";
$s = $a[$k] ?? "";
$t = null;
$inTransaction = $t !== null && isset($t->Connection);
$c = $inTransaction ? $t->Connection : null;
if ($c === null) {
    $c = new stdClass();
    $c->state = "open";
}
if (($c->state ?? null) !== "open") {
    $c->state = "open";
}
echo $s;
?>
--EXPECT--
