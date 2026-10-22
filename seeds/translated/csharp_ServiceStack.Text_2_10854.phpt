--TEST--
Translated seed (inferredbugs-csharp: ServiceStack.Text/2)
--FILE--
<?php
$map = [];
$map["1"] = null;
$map[1] = "x";
$map["01"] = null;
$map[true] = false;
$map[1.0] = null;
$map[null] = "n";
$map[""] = null;

$out = "{";
$first = true;
foreach ($map as $k => $v) {
    if ($v === null) {
        continue;
    }
    if (!$first) {
        $out .= ",";
    }
    $first = false;
    $key = is_bool($k) ? ($k ? "true" : "false") : (is_null($k) ? "null" : (string)$k);
    if (is_string($k) && preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $k)) {
        $out .= $key . ":";
    } else {
        $out .= json_encode($key, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . ":";
    }
    $out .= json_encode($v, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
$out .= "}";
echo $out;
?>
--EXPECT--
