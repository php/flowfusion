--TEST--
Translated seed (inferredbugs-csharp: graze/1)
--FILE--
<?php
$opts = getopt("t:o:tf:of:hs sfe:c:a:", ["template:", "output:", "templatefile:", "outputfile:", "help", "skip", "skipfile", "extras:", "configuration:", "assets:"]);
$help = array_key_exists("h", $opts) || array_key_exists("help", $opts);
if ($help) {
    echo "Usage\n";
    exit(0);
}
$copyAssets = !(array_key_exists("s", $opts) || array_key_exists("skip", $opts));
$copyFile = !(array_key_exists("sf", $opts) || array_key_exists("skipfile", $opts));
$templateRoot = $opts["t"] ?? $opts["template"] ?? null;
$outputRoot = $opts["o"] ?? $opts["output"] ?? null;
$templateFile = $opts["tf"] ?? $opts["templatefile"] ?? null;
$outputFile = $opts["of"] ?? $opts["outputfile"] ?? null;
$extrasRoot = $opts["e"] ?? $opts["extras"] ?? null;
$configurationFile = $opts["c"] ?? $opts["configuration"] ?? null;
$templateAssetsFolder = $opts["a"] ?? $opts["assets"] ?? null;

$root = $outputRoot ?? sys_get_temp_dir() . DIRECTORY_SEPARATOR . "php_port_" . bin2hex(random_bytes(4));
if (!is_dir($root)) {
    mkdir($root, 0777, true);
}
if ($copyAssets) {
    $assets = $root . DIRECTORY_SEPARATOR . "assets";
    if (!is_dir($assets)) {
        mkdir($assets, 0777, true);
    }
    file_put_contents($assets . DIRECTORY_SEPARATOR . "x.txt", (string)$templateAssetsFolder);
}
if ($copyFile) {
    file_put_contents($root . DIRECTORY_SEPARATOR . ($outputFile ?? "index.html"), (string)$templateFile . "|" . (string)$configurationFile . "|" . (string)$extrasRoot);
}
echo "Static site created successfully\n";
?>
--EXPECT--
