--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/37)
--FILE--
<?php
$keys = ["File","Tools","Ciphers","Compression","Settings","Open","OpenWith","SaveAll","TextSequenceSearcher","BatchExtractor","BatchInjector","Hashes","Encrypt","Decrypt","Decompress","Compress","RawImageViewer","IncludeDevBuilds","ChangeLanguage","AboutKuriimu2"];
$menu = [];
foreach ($keys as $k) $menu[$k] = strrev($k);
$menu["IncludeDevBuilds"] = (bool)($menu["IncludeDevBuilds"] ?? false);
$menu["Checked"] = $menu["IncludeDevBuilds"] ? 1 : 0;
$items = [
    ["Text" => $menu["File"], "Items" => [$menu["Open"], $menu["OpenWith"], null, $menu["SaveAll"]]],
    ["Text" => $menu["Tools"], "Items" => [$menu["BatchExtractor"], $menu["BatchInjector"], $menu["TextSequenceSearcher"], $menu["Hashes"], $menu["RawImageViewer"]]],
    ["Text" => $menu["Ciphers"], "Items" => [$menu["Decrypt"], $menu["Encrypt"]]],
    ["Text" => $menu["Compression"], "Items" => [$menu["Decompress"], $menu["Compress"]]],
    ["Text" => $menu["Settings"], "Items" => [
        ["Text" => $menu["IncludeDevBuilds"], "Checked" => $menu["Checked"]],
        ["Text" => $menu["ChangeLanguage"], "Items" => ["English", "Deutsch", "Dansk", "русский", "简体中文"]]
    ]]
];
$layout = ["Title" => "Kuriimu2", "ClientSize" => [1116, 643], "Padding" => 3, "Menu" => $items, "AboutItem" => $menu["AboutKuriimu2"], "Content" => ["TabControl" => [], "Progress" => [0, ""]]];
echo "";
?>
--EXPECT--
