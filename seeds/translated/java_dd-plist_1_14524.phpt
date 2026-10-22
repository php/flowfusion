--TEST--
Translated seed (inferredbugs-java: dd-plist/1)
--FILE--
<?php
$filePath = 'test.plist';
$bytes = file_get_contents($filePath);
$magic_string = substr($bytes, 0, 8);
if (strncmp($magic_string, "bplist00", 8) === 0) {
    // Simulating binary plist parsing
} elseif (strncmp($magic_string, "<?xml", 5) === 0) {
    // Simulating XML plist parsing
} else {
    // Simulating unsupported format handling
}
?>
--EXPECT--
