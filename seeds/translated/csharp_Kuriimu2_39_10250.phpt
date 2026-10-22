--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/39)
--FILE--
<?php
declare(strict_types=1);

$keys = [
    "File","Tools","Ciphers","Compression","Settings",
    "Open","OpenWith","SaveAll",
    "TextSequenceSearcher","BatchExtractor","BatchInjector",
    "Hashes","Encrypt","Decrypt","Decompress","Compress",
    "RawImageViewer","IncludeDevBuilds","ChangeLanguage",
    "ChangeTheme","ThemeDark","ThemeLight","AboutKuriimu2"
];

$menu = [];
foreach ($keys as $k) {
    $menu[$k] = ["text" => $k, "items" => []];
}

$menu["File"]["items"] = [$menu["Open"], $menu["OpenWith"], null, $menu["SaveAll"]];
$menu["Tools"]["items"] = [$menu["BatchExtractor"], $menu["BatchInjector"], $menu["TextSequenceSearcher"], $menu["Hashes"], $menu["RawImageViewer"]];
$menu["Ciphers"]["items"] = [$menu["Decrypt"], $menu["Encrypt"]];
$menu["Compression"]["items"] = [$menu["Decompress"], $menu["Compress"]];
$menu["Settings"]["items"] = [
    ["text" => "IncludeDevBuilds", "checked" => false, "command" => []],
    ["text" => "ChangeLanguage", "items" => [
        ["text" => "English"], ["text" => "Deutsch"], ["text" => "Dansk"], ["text" => "русский"], ["text" => "简体中文"]
    ]],
    ["text" => "ChangeTheme", "items" => [
        ["text" => "ThemeDark"], ["text" => "ThemeLight"]
    ]]
];

$layout = [
    "title" => "Kuriimu2",
    "size" => [1116, 643],
    "padding" => 3,
    "allowDrop" => true,
    "menu" => $menu,
    "content" => [
        "tabs" => [],
        "progress" => ["value" => 0, "status" => ""]
    ]
];

echo "";
?>
--EXPECT--
