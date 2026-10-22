--TEST--
Translated seed (inferredbugs-csharp: ZKEACMS/2)
--FILE--
<?php
$theme = ['Theme' => ['Title' => "x\0y"]];
$json = json_encode($theme, JSON_UNESCAPED_UNICODE);
$decoded = json_decode($json, true);
$bytes = $json;
$len = strlen($bytes);
for ($i = 0; $i < $len; $i++) {
    $c = ord($bytes[$i]);
    if ($c === 0) {
        $bytes[$i] = "\x01";
    }
}
$out = json_encode(['package' => $decoded, 'size' => strlen($bytes), 'name' => $decoded['Theme']['Title'] . '.theme'], JSON_UNESCAPED_UNICODE);
echo $out;
?>
--EXPECT--
