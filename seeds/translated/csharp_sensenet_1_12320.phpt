--TEST--
Translated seed (inferredbugs-csharp: sensenet/1)
--FILE--
<?php
$inputs = [
    "",
    "xxx{\"a\":1,\"b\":[2,3]}",
    "prefix[{\"x\":true}]",
    "noise{\"nested\":{\"k\":\"v\"}}",
    "[]",
    "[{\"only\":null}]",
];

foreach ($inputs as $s) {
    if ($s === '') {
        $v = null;
    } else {
        $last = substr($s, -1);
        $firstChar = $last === ']' ? '[' : '{';
        $p = strpos($s, $firstChar);
        if ($p > 0) $s = substr($s, $p);
        $v = json_decode($s, true);
        if (json_last_error() !== JSON_ERROR_NONE) $v = null;
        if (is_array($v) && array_is_list($v)) $v = $v[0] ?? null;
    }
    if ($v !== null) {
        $out = json_encode($v, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($out === false) $out = "";
        echo $out, "\n";
    }
}

$paths = [
    "/Root/MyFolder/MyDocument.doc",
    "/Root/",
    "Root",
    "/a/b/c/",
    "/single",
];
foreach ($paths as $path) {
    $path = rtrim($path, '/');
    $p = strrpos($path, '/');
    $entityUrl = $p < 0 ? "($path)" : substr($path, 0, $p) . "('" . substr($path, $p + 1) . "')";
    echo $entityUrl, "\n";
}

$odataPaths = [
    "/Root",
    "/Root/MyFolder/MyDocument.doc",
];
foreach ($odataPaths as $path) {
    if (strcasecmp($path, "/Root") === 0) {
        echo "\n";
    } else {
        $parent = dirname($path);
        $name = basename($path);
        echo $parent . "('" . $name . "')", "\n";
    }
}
?>
--EXPECT--
