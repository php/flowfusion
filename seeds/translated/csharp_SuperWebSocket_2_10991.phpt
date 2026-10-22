--TEST--
Translated seed (inferredbugs-csharp: SuperWebSocket/2)
--FILE--
<?php
$lines = [
    "GET /chat HTTP/1.1",
    "Host: example.com",
    "X-Test: one",
    "\ttwo",
    "X-Test: three",
    "Content-Type: text/plain",
    "",
];
$session = new stdClass();
$session->Items = [];
$session->Method = '';
$session->Path = '';
$session->HttpVersion = '';
$firstLine = '';
$prevKey = '';
foreach ($lines as $line) {
    if ($line === '') break;
    if ($firstLine === '') {
        $firstLine = $line;
        continue;
    }
    if (strlen($line) > 0 && $line[0] === "\t" && $prevKey !== '') {
        $session->Items[$prevKey] = ($session->Items[$prevKey] ?? '') . trim($line);
        continue;
    }
    $pos = strpos($line, ':');
    if ($pos === false || $pos <= 0) continue;
    $key = trim(substr($line, 0, $pos));
    $valueOffset = $pos + 1;
    if (strlen($line) <= $valueOffset) continue;
    $value = substr($line, $valueOffset);
    if ($value !== '' && $value[0] === ' ' && strlen($value) > 1) $value = substr($value, 1);
    if ($key === '') continue;
    if (!array_key_exists($key, $session->Items)) $session->Items[$key] = $value;
    else $session->Items[$key] .= ', ' . $value;
    $prevKey = $key;
}
$metaInfo = explode(' ', $firstLine);
$session->Method = $metaInfo[0] ?? '';
$session->Path = $metaInfo[1] ?? '';
$session->HttpVersion = $metaInfo[2] ?? '';
echo json_encode($session, JSON_UNESCAPED_SLASHES), PHP_EOL;
?>
--EXPECT--
