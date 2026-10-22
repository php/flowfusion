--TEST--
Translated seed (inferredbugs-csharp: Oryx/5)
--FILE--
<?php
$version = false;
for ($i = 1; $i < $argc; $i++) {
    if ($argv[$i] === '--version' || $argv[$i] === '-v') {
        $version = true;
        break;
    }
}

if ($version) {
    echo "Version: 1.0.0\n";
    echo "Commit: unknown\n";
} else {
    echo "Usage: php " . basename($argv[0]) . " [--version]\n";
}
exit(0);
?>
--EXPECT--
