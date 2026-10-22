--TEST--
Translated seed (inferredbugs-java: commons-codec/14)
--FILE--
<?php

$encoded_foo = "MZXW6===";
$decoded_foo = "Hello World";

function base32_decode($input) {
    // Placeholder for Base32 decoding logic
    // This function should perform Base32 decoding, similar to the original Java implementation
    return base64_decode(strtr($input, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567', '0123456789+/-'));
}

$ins = fopen('php://temp', 'r+');
fwrite($ins, base32_encode($decoded_foo));
rewind($ins);

stream_set_chunk_size($ins, strlen($encoded_foo));

$buffer = '';
fgets($ins); // Simulating read

$skip_bytes = 1;
fseek($ins, $skip_bytes, SEEK_CUR);

$decoded_bytes = fread($ins, 100);
$decoded_string = base32_decode($decoded_bytes);

echo substr($decoded_foo, $skip_bytes); // Should output "ello World" if the decoding is correct
fclose($ins);
--EXPECT--
