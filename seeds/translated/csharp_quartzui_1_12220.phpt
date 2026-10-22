--TEST--
Translated seed (inferredbugs-csharp: quartzui/1)
--FILE--
<?php
$urls = [
    "http://example.com/a",
    "http://example.com/b",
    "https://example.com/c",
    "https://example.com/d",
    "http://sub.example.com/e",
    "http://sub.example.com/f",
];

$pool = [];
foreach ($urls as $u) {
    $p = parse_url($u);
    $k = ($p["scheme"] ?? "") . ($p["host"] ?? "");
    if (!isset($pool[$k])) {
        $pool[$k] = [];
    }
    $pool[$k][] = $u;
}

$headers = [
    "X-Test" => "1",
    "X-Test" => "2",
    "Content-Type" => "application/json",
    "content-type" => "text/plain",
];

$normalized = [];
foreach ($headers as $k => $v) {
    $normalized[strtolower($k)] = $v;
}

$payloads = ["", "   ", null, "{}", "{\"a\":1}", [1, 2, 3], ["x" => ["y" => 1]]];
foreach ($payloads as $p) {
    if ($p === null || (is_string($p) && trim($p) === "")) {
        $p = "{}";
    } elseif (!is_string($p)) {
        $p = json_encode($p);
    }
    json_decode($p, true);
}

foreach ($pool as $k => $v) {
    sort($v);
}

echo "ok\n";
?>
--EXPECT--
