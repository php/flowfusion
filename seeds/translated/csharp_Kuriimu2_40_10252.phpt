--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/40)
--FILE--
<?php
$keys = ["File","Tools","Ciphers","Compression","Settings","Open","OpenWith","SaveAll","TextSequenceSearcher","BatchExtractor","BatchInjector","Hashes","Encrypt","Decrypt","Decompress","Compress","RawImageViewer","IncludeDevBuilds","ChangeLanguage","ChangeTheme","ThemeDark","ThemeLight","AboutKuriimu2"];
$menu = [];
foreach ($keys as $k) $menu[$k] = ["text" => $k, "items" => []];
$menu["File"]["items"] = [$menu["Open"], $menu["OpenWith"], null, $menu["SaveAll"]];
$menu["Tools"]["items"] = [$menu["BatchExtractor"], $menu["BatchInjector"], $menu["TextSequenceSearcher"], $menu["Hashes"], $menu["RawImageViewer"]];
$menu["Ciphers"]["items"] = [$menu["Decrypt"], $menu["Encrypt"]];
$menu["Compression"]["items"] = [$menu["Decompress"], $menu["Compress"]];
$menu["Settings"]["items"] = [
    ["text" => $keys[17], "checked" => false, "command" => null],
    ["text" => $keys[18], "items" => [["text" => "English"], ["text" => "Deutsch"], ["text" => "Dansk"], ["text" => "русский"], ["text" => "简体中文"]]],
    ["text" => $keys[19], "items" => [["text" => $keys[20]], ["text" => $keys[21]]]],
];
$menu["About"] = $keys[22];
$json = json_encode($menu, JSON_UNESCAPED_UNICODE);
echo substr($json, 0, 1) . substr($json, 1);
?>
--EXPECT--
