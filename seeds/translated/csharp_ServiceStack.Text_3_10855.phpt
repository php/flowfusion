--TEST--
Translated seed (inferredbugs-csharp: ServiceStack.Text/3)
--FILE--
<?php
$map = [];
$map["1"] = "a";
$map[1] = "b";
$map["01"] = null;
$map[true] = "c";
$map[false] = null;
$map[1.0] = "d";
$map["1.0"] = "e";
$map[""] = "";
$map["0"] = 0;
$map[0] = "z";

$out = "{";
$first = true;
$lastType = null;
foreach ($map as $k => $v) {
    if ($v === null) continue;
    $type = gettype($k);
    if (!$first) $out .= ",";
    $first = false;
    if ($type === "string") {
        $out .= json_encode((string)$k);
    } elseif ($type === "integer") {
        $out .= (string)$k;
    } elseif ($type === "double") {
        $out .= json_encode((string)$k);
    } elseif ($type === "boolean") {
        $out .= $k ? "true" : "false";
    } else {
        $out .= json_encode((string)$k);
    }
    $out .= ":";
    $vtype = gettype($v);
    if ($vtype === "string") {
        $out .= json_encode($v);
    } elseif ($vtype === "integer" || $vtype === "double") {
        $out .= (string)$v;
    } elseif ($vtype === "boolean") {
        $out .= $v ? "true" : "false";
    } else {
        $out .= "null";
    }
    $lastType = $type;
}
$out .= "}";
echo $out;
?>
--EXPECT--
