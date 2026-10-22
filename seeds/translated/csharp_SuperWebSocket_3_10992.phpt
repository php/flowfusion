--TEST--
Translated seed (inferredbugs-csharp: SuperWebSocket/3)
--FILE--
<?php
$h = "GET /chat HTTP/1.1\r\nHost: example.com\r\nX-Test: one\r\n\ttwo\r\nX-Test: three\r\nX-Empty:\r\nX-Spaces:  value\r\n\r\n";
$lines = preg_split("/\r\n|\n|\r/", $h);
$items = [];
$firstLine = "";
$prevKey = "";
foreach ($lines as $line) {
    if ($line === "") break;
    if ($firstLine === "") {
        $firstLine = $line;
        continue;
    }
    if (isset($line[0]) && $line[0] === "\t" && $prevKey !== "") {
        $items[$prevKey] = ($items[$prevKey] ?? "") . trim($line);
        continue;
    }
    $pos = strpos($line, ":");
    if ($pos === false || $pos <= 0) continue;
    $key = trim(substr($line, 0, $pos));
    if ($key === "") continue;
    $value = substr($line, $pos + 1);
    if ($value !== "" && isset($value[0]) && $value[0] === " " && strlen($value) > 1) $value = substr($value, 1);
    if (!array_key_exists($key, $items)) $items[$key] = $value;
    else $items[$key] .= ", " . $value;
    $prevKey = $key;
}
$meta = explode(" ", $firstLine);
$method = $meta[0] ?? "";
$path = $meta[1] ?? "";
$version = $meta[2] ?? "";
echo $method . " " . $path . " " . $version . "\n";
echo json_encode($items, JSON_UNESCAPED_SLASHES) . "\n";
?>
--EXPECT--
