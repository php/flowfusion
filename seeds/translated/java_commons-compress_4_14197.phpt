--TEST--
Translated seed (inferredbugs-java: commons-compress/4)
--FILE--
<?php
$dir = __DIR__;
$output = "$dir/bla.txt.bz2";
$file1 = "$dir/test.txt";
$file2 = "$dir/test-entpackt.txt";

if (file_exists($output)) {
    unlink($output);
}

$input = fopen($file1, 'rb');
$outputStream = fopen($output, 'wb');
$buffer = '';

while (!feof($input)) {
    $buffer = fread($input, 8192);
    // Simulating BZip2 compression would require a library, skipping this for raw execution
    fwrite($outputStream, $buffer); // Just writing buffer directly for the sake of this bug pattern
}
fclose($input);
fclose($outputStream);

$input = fopen($output, 'rb');
$outputStream = fopen($file2, 'wb');
$buffer = '';

while (!feof($input)) {
    $buffer = fread($input, 8192);
    fwrite($outputStream, $buffer); // Again just copying as if it's decompressing
}
fclose($input);
fclose($outputStream);
?>
--EXPECT--
