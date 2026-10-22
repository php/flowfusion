--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/33)
--FILE--
<?php
$keys = ["File","Tools","Ciphers","Compression","Settings","Open","OpenWith","SaveAll","TextSequenceSearcher","BatchExtractor","BatchInjector","Hashes","Encrypt","Decrypt","Decompress","Compress","RawImageViewer","IncludeDevBuilds","ChangeLanguage","AboutKuriimu2"];
$menu = [];
$menu[] = ["text" => $keys[0], "items" => [$keys[5], $keys[6], null, $keys[7]]];
$menu[] = ["text" => $keys[1], "items" => [$keys[9], $keys[10], $keys[8], $keys[11], $keys[16]]];
$menu[] = ["text" => $keys[2], "items" => [$keys[13], $keys[12]]];
$menu[] = ["text" => $keys[3], "items" => [$keys[14], $keys[15]]];
$menu[] = ["text" => $keys[4], "items" => [["text" => $keys[17], "checked" => false], ["text" => $keys[18], "items" => ["English", "Deutsch"]]]];
$menu["about"] = $keys[19];
$state = ["title" => "Kuriimu2", "size" => [1116, 643], "padding" => 3, "allowDrop" => true, "tabs" => [], "progress" => ["value" => 0, "text" => ""]];
foreach ($menu as $k => $v) {
    if (is_array($v) && isset($v["items"])) {
        foreach ($v["items"] as $i => $item) {
            if ($item === null) $v["items"][$i] = "";
        }
    }
}
echo "";
?>
--EXPECT--
