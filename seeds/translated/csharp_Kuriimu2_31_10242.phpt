--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/31)
--FILE--
<?php
$keys = ["File","Tools","Ciphers","Compression","Settings","Open","OpenWith","SaveAll","TextSequenceSearcher","BatchExtractor","BatchInjector","Hashes","Encrypt","Decrypt","Decompress","Compress","RawImageViewer","IncludeDevBuilds","AboutKuriimu2"];
$menu = [];
foreach ($keys as $k) $menu[$k] = ["text" => $k, "checked" => ($k === "IncludeDevBuilds") ? (bool)random_int(0, 1) : null];
$menu["File"]["items"] = ["Open","OpenWith",null,"SaveAll"];
$menu["Tools"]["items"] = ["BatchExtractor","BatchInjector","TextSequenceSearcher","Hashes","RawImageViewer"];
$menu["Ciphers"]["items"] = ["Decrypt","Encrypt"];
$menu["Compression"]["items"] = ["Decompress","Compress"];
$menu["Settings"]["items"] = [["text" => "IncludeDevBuilds", "checked" => $menu["IncludeDevBuilds"]["checked"]]];
$state = ["title" => "Kuriimu2", "size" => [1116, 643], "padding" => 3, "menu" => $menu, "about" => "AboutKuriimu2"];
$json = json_encode($state, JSON_UNESCAPED_SLASHES);
echo $json === false ? "" : $json;
?>
--EXPECT--
