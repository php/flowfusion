--TEST--
Translated seed (inferredbugs-java: commons-codec/38)
--FILE--
<?php

$encodedB64 = "AAAA////";
$stringFixture = "Hello World";

$bos = fopen('php://temp', 'r+');
$base64os = base64_encode(fwrite($bos, $stringFixture));
rewind($bos);

$ins = fopen('php://temp', 'r+');
fwrite($ins, $base64os);
rewind($ins);

fgets($ins); // Skip first character
$decodedBytes = base64_decode(fread($ins, 64));
$str = $decodedBytes;

echo substr($str, 1); // Outputs "ello World"

$ins2 = fopen('php://temp', 'r+');
fwrite($ins2, $encodedB64);
rewind($ins2);

$available = strlen($encodedB64);
$skip = min($available, 10);
$skipped = fread($ins2, $skip);
$remaining = fread($ins2, $available - $skip);

if (strlen($remaining) === 0) {
    echo "EOF reached";
} else {
    echo "Data remaining";
}

fclose($bos);
fclose($ins);
fclose($ins2);
?>
--EXPECT--
