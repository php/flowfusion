--TEST--
Translated seed (inferredbugs-csharp: PowerShell_Credential_Manager/1)
--FILE--
<?php
$major = (int)PHP_VERSION_ID / 10000;
if ($major < 7) {
    exit(0);
}

$a = [];
$a["0"] = "x";
$a[0] = "y";
$b = [];
$b["01"] = 1;
$b[1] = 2;
$c = [];
$c[true] = "t";
$c[1] = "u";

$s = "2147483647";
$n = $s + 1;
$m = (int)($n - 1);

$values = [null, false, true, 0, 1, 1.0, "1", "01", "1e2", "foo", [], [1], new stdClass()];
foreach ($values as $v) {
    $x = is_array($v) ? count($v) : (is_object($v) ? count(get_object_vars($v)) : (int)@intval($v));
    $y = @json_encode($v);
    $z = @serialize($v);
}

$h = fopen("php://memory", "r+");
fwrite($h, "abc");
rewind($h);
fread($h, 2);
fseek($h, 1, SEEK_SET);
fwrite($h, "Z");
rewind($h);
stream_get_contents($h);
fclose($h);

$u = [];
$u[] = $a;
$u[] = $b;
$u[] = $c;
$u[] = $m;
$u[] = $n;
$u[] = $values;

echo "";
?>
--EXPECT--
