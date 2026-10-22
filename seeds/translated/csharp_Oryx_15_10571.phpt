--TEST--
Translated seed (inferredbugs-csharp: Oryx/15)
--FILE--
<?php
$json = "{\"a\":1,\"b\":[true,false,null],\"c\":{\"d\":\"x\"},\"e\":1.0,\"f\":\"\\u0000\"}";
$yaml = "a: 1\nb:\n  - true\n  - false\n  - null\nc:\n  d: x\ne: 1.0\nf: \"\\0\"\n";
$toml = "a = 1\nb = [true, false, null]\n[c]\nd = \"x\"\ne = 1.0\nf = \"\\u0000\"\n";

json_decode($json, true, 512, JSON_THROW_ON_ERROR);

if (function_exists('yaml_parse')) {
    yaml_parse($yaml);
}

if (function_exists('parse_ini_string')) {
    parse_ini_string($toml, true, INI_SCANNER_TYPED);
}

echo "ok\n";
?>
--EXPECT--
