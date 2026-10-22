--TEST--
Translated seed (inferredbugs-csharp: ServiceStack.Text/1)
--FILE--
<?php
$map = ["0" => null, 0 => "x", "01" => null, 1 => "y", "" => null, false => "z"];
$out = "{";
$first = true;
foreach ($map as $k => $v) {
    if ($v === null) continue;
    if (!$first) $out .= ",";
    $first = false;
    $out .= json_encode((string)$k) . ":" . json_encode($v);
}
$out .= "}";
echo $out, PHP_EOL;
?>
--EXPECT--
