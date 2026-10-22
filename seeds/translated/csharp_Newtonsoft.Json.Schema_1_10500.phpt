--TEST--
Translated seed (inferredbugs-csharp: Newtonsoft.Json.Schema/1)
--FILE--
<?php
$values = [0, 1, "1", "01", "1e0", true, false, null, [], [1], (object)[], "foo", "0", 0.0, -0, PHP_INT_MAX, PHP_INT_MIN];
$patterns = [
    fn($v) => is_int($v) || is_float($v) || is_string($v) && is_numeric($v),
    fn($v) => $v === 1 || $v === "1" || $v === true,
    fn($v) => $v === 0 || $v === "0" || $v === false || $v === null,
];
foreach ($values as $v) {
    $if = $patterns[0]($v);
    if ($if) {
        if ($patterns[1]($v)) {
            $x = json_encode(["then" => $v], JSON_THROW_ON_ERROR);
            json_decode($x, true, 512, JSON_THROW_ON_ERROR);
        }
    } else {
        if ($patterns[2]($v)) {
            $x = json_encode(["else" => $v], JSON_THROW_ON_ERROR);
            json_decode($x, true, 512, JSON_THROW_ON_ERROR);
        }
    }
}
for ($i = 0; $i < 3; $i++) {
    $v = $values[$i + 1];
    $a = $patterns[0]($v);
    $b = $patterns[1]($v);
    $c = $patterns[2]($v);
    $s = ($a ? ($b ? "then" : "skip") : ($c ? "else" : "skip")) . ":" . gettype($v);
    strlen($s);
}
?>
--EXPECT--
