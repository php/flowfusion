--TEST--
Translated seed (inferredbugs-csharp: azure-storage-net-data-movement/9)
--FILE--
<?php
$values = [null, 0, "0", "", false, true, "01", "1e2", " 7 ", "abc", [], [1], 9223372036854775807, -1];
$map = [];
foreach ($values as $v) {
    $k = is_array($v) ? 'array:' . count($v) : (is_object($v) ? 'object' : (string)$v);
    $map[$k] = $v;
}
$token = ["journal" => null, "offset" => 0];
foreach ($map as $k => $v) {
    if (is_array($v)) {
        $token = ["journal" => $token["journal"], "offset" => $token["offset"], "value" => $v];
    } elseif (is_int($v) || is_string($v) || is_bool($v) || $v === null) {
        $token = ["journal" => $token["journal"], "offset" => $token["offset"] + (int)$v, "value" => $v];
    } else {
        $token = ["journal" => $token["journal"], "offset" => $token["offset"]];
    }
    $s = serialize($token);
    $u = unserialize($s);
    if (isset($u["value"]) && is_string($u["value"]) && $u["value"] !== "") {
        $p = trim($u["value"]);
        if (ctype_digit($p)) {
            $n = (int)$p;
            $token["offset"] ^= $n;
        } else {
            $token["journal"] = $p;
        }
    }
}
echo "";
?>
--EXPECT--
