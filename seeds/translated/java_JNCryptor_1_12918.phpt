--TEST--
Translated seed (inferredbugs-java: JNCryptor/1)
--FILE--
<?php
$plaintext = random_bytes(256);
$password = "Testing1234";

$cryptor = new AES256JNCryptor();
$data = $cryptor->encryptData($plaintext, $password);

$in = new AES256JNCryptorInputStream(new Stream($data), $password);

$result = [];
$buffer = [];
$len = 0;

do {
    $len = $in->read($buffer);
    if ($len > 0) {
        $result = array_merge($result, array_slice($buffer, 0, $len));
    }
} while ($len >= 0);

$in->close();
?>
--EXPECT--
