--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/89)
--FILE--
<?php
$json = '{"a":1,"b":null,"c":[1,2,3],"d":{"x":"y"}}';
$decoded = json_decode($json, true);
$encoded = json_encode($decoded, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
$roundtrip = json_decode($encoded, true);
if (is_array($roundtrip) && array_key_exists('a', $roundtrip) && !array_key_exists('b', $roundtrip)) {
    $roundtrip['e'] = [0, 1, 2, 3];
    $out = json_encode($roundtrip, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    echo $out;
}
?>
--EXPECT--
