--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/43)
--FILE--
<?php
$keys = ["File","Tools","Ciphers","Compression","Settings","Open","OpenWith","SaveAll","TextSequenceSearcher","BatchExtractor","BatchInjector","Hashes","Encrypt","Decrypt","Decompress","Compress","RawImageViewer","IncludeDevBuilds","ChangeLanguage","ChangeTheme","ThemeDark","ThemeLight","AboutKuriimu2"];
$menu = [];
$menu["File"] = ["Open","OpenWith","SaveAll"];
$menu["Tools"] = ["BatchExtractor","BatchInjector","TextSequenceSearcher","Hashes","RawImageViewer"];
$menu["Ciphers"] = ["Decrypt","Encrypt"];
$menu["Compression"] = ["Decompress","Compress"];
$menu["Settings"] = ["IncludeDevBuilds","ChangeLanguage" => ["English","Deutsch","Dansk","русский","简体中文"], "ChangeTheme" => ["ThemeDark","ThemeLight"]];
$menu["AboutItem"] = "AboutKuriimu2";
$json = json_encode($menu, JSON_UNESCAPED_UNICODE);
for ($i = 0; $i < 3; $i++) {
    $decoded = json_decode($json, true);
    $flat = [];
    array_walk_recursive($decoded, function($v) use (&$flat) { $flat[] = $v; });
    sort($flat);
    $json = json_encode($flat, JSON_UNESCAPED_UNICODE);
}
echo $json, PHP_EOL;
?>
--EXPECT--
