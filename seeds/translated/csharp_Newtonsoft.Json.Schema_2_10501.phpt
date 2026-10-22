--TEST--
Translated seed (inferredbugs-csharp: Newtonsoft.Json.Schema/2)
--FILE--
<?php
$values = [0, 1, "1", "01", "foo", null, true, false, 1.0, -1, 2147483647, 2147483648];

foreach ($values as $v) {
    $if = is_int($v) || (is_string($v) && ctype_digit($v));
    if ($if) {
        if (is_int($v) || (is_string($v) && strlen($v) > 0 && $v[0] !== '0')) {
            $then = true;
        } else {
            $then = false;
        }
        if (!$then) {
            $x = json_encode(["then" => $v], JSON_THROW_ON_ERROR);
            $y = json_decode($x, true, 512, JSON_THROW_ON_ERROR);
        }
    } else {
        if ($v === null || $v === false) {
            $else = true;
        } else {
            $else = false;
        }
        if (!$else) {
            $x = json_encode(["else" => $v], JSON_THROW_ON_ERROR);
            $y = json_decode($x, true, 512, JSON_THROW_ON_ERROR);
        }
    }
}

$shared = ["schema" => "same"];
$cache = [];
for ($i = 0; $i < 3; $i++) {
    $k = spl_object_id((object)$shared);
    if (!isset($cache[$k])) {
        $cache[$k] = $shared;
    }
    $tmp = $cache[$k];
    $tmp["i"] = $i;
    $cache[$k] = $tmp;
}

echo "";
?>
--EXPECT--
