--TEST--
Translated seed (inferredbugs-java: commons-codec/40)
--FILE--
<?php

function base64_decode_stream($encoded) {
    return base64_decode($encoded);
}

$encoded_b64 = "AAAA////";
$decoded_bytes = base64_decode_stream($encoded_b64);
$expected = "\0\0\0\xFF\xFF\xFF";
if ($decoded_bytes === $expected) {
    echo "Decoded correctly.\n";
} else {
    echo "Decoded incorrectly.\n";
}

$input_stream = fopen('data://text/plain;base64,' . base64_encode($encoded_b64), 'r');
$bytes_read = fread($input_stream, 6);
if ($bytes_read !== false) {
    echo "Read: " . bin2hex($bytes_read) . "\n";
} else {
    echo "Read failed.\n";
}
fclose($input_stream);
--EXPECT--
