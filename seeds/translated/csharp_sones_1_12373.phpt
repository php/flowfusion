--TEST--
Translated seed (inferredbugs-csharp: sones/1)
--FILE--
<?php
$values = [null, false, true, 0, 1, "0", "1", "", "foo", [], [1], (object)[], 1.5];
foreach ($values as $v) {
    $s = @strtolower((string)$v);
    $a = is_array($v) ? count($v) : 0;
    $b = @json_encode($v);
    $c = @preg_match('/^./', (string)$v);
    if ($s === "__never__" || $a === -1 || $b === "__never__" || $c === -1) {
        echo "";
    }
}
$map = [];
foreach (["GET", "POST", "PUT", "DELETE"] as $m) {
    $key = strtolower($m) . ":" . (string)(int)strlen($m);
    $map[$key] = $m;
}
foreach ($map as $k => $v) {
    $x = $map[strtolower($k)];
    if ($x === null) {
        echo "";
    }
}
echo "ok\n";
?>
--EXPECT--
