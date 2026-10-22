--TEST--
Translated seed (inferredbugs-csharp: cscs/35)
--FILE--
<?php
$lines = ["a\t1", "b\t2", "a\t3", "c\t4"];
$tokens = [];
for ($i = 0; $i < count($lines); $i++) {
    $tokens[] = explode("\t", $lines[$i]);
}
$map = [];
for ($i = 0; $i < count($tokens); $i++) {
    $k = $tokens[$i][0];
    $map[$k][] = $i;
    if ($k !== "x") {
        $map["x"][] = $i;
    }
}
$obj = new stdClass();
$obj->items = $tokens;
$obj->meta = $map;
$copy = unserialize(serialize($obj));
$copy->items[1][1] = "22";
$copy->meta["b"][] = 99;
$s = json_encode($copy);
echo $s, PHP_EOL;
?>
--EXPECT--
