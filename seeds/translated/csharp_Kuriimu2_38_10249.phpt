--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/38)
--FILE--
<?php
$items = [];
$items[] = ["File", ["Open", "OpenWith", null, "SaveAll"]];
$items[] = ["Tools", ["BatchExtractor", "BatchInjector", "TextSequenceSearcher", "Hashes", "RawImageViewer"]];
$items[] = ["Ciphers", ["Decrypt", "Encrypt"]];
$items[] = ["Compression", ["Decompress", "Compress"]];
$items[] = ["Settings", [
    ["IncludeDevBuilds", false],
    ["ChangeLanguage", ["English", "Deutsch", "Dansk", "русский", "简体中文"]],
    ["Themes", ["Dark", "Light"]],
]];
$items[] = ["AboutKuriimu2"];
foreach ($items as $menu) {
    foreach ($menu as $entry) {
        if (is_array($entry)) {
            foreach ($entry as $sub) {
                if (is_array($sub)) {
                    foreach ($sub as $leaf) {
                        (string)$leaf;
                    }
                } else {
                    (string)$sub;
                }
            }
        } else {
            (string)$entry;
        }
    }
}
echo "Kuriimu2\n";
?>
--EXPECT--
