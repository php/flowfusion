--TEST--
Translated seed (inferredbugs-csharp: Oryx/1)
--FILE--
<?php
$source = '';
if ($source === '' || !is_dir($source)) {
    fwrite(STDOUT, $source === '' ? "Usage:\n" : "Could not find the source code folder\n");
    exit(1);
}
echo "script\n";
?>
--EXPECT--
