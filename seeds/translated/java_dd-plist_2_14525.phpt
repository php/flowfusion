--TEST--
Translated seed (inferredbugs-java: dd-plist/2)
--FILE--
<?php
$handle = fopen('php://temp', 'r+');
fwrite($handle, "bplist00");
rewind($handle);
$magic = fread($handle, 8);
if (strpos($magic, 'bplist00') === 0) {
    // Simulate processing binary
} elseif (strpos($magic, '<?xml') === 0) {
    // Simulate processing XML
} else {
    throw new Exception("The given data is neither a binary nor a XML property list.");
}
fclose($handle);
--EXPECT--
