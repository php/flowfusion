--TEST--
Translated seed (inferredbugs-csharp: PaySharp/1)
--FILE--
<?php
$data = "abc";
echo strtoupper(md5($data)) . PHP_EOL;

$priv = "MIIBOgIBAAJBALm6n4Qm6X5Q6fQ5c0m1v3m8q3w8m1v3m8q3w8m1v3m8q3w8m1v3m8q3w8CAwEAAQJBAK1xwQ0Q3v5m1v3m8q3w8m1v3m8q3w8m1v3m8q3w8m1v3m8q3w8m1v3m8q3w8m1v3m8q3w8CIQDh4wIDAQAB";
$bin = base64_decode($priv, true);
if ($bin !== false && strlen($bin) > 0) {
    $first = ord($bin[0]);
    if ($first === 0x30) {
        $x = 0;
        for ($i = 0; $i < strlen($bin); $i++) {
            $x ^= ord($bin[$i]);
        }
        echo $x . PHP_EOL;
    }
}

$bad = "-----BEGIN RSA PRIVATE KEY-----\n" . chunk_split(base64_encode(random_bytes(32)), 64, "\n") . "-----END RSA PRIVATE KEY-----";
$start = strpos($bad, "-----BEGIN RSA PRIVATE KEY-----\n");
$end = strpos($bad, "-----END RSA PRIVATE KEY-----", $start + 32);
if ($start !== false && $end !== false) {
    $body = substr($bad, $start + 32, $end - ($start + 32));
    base64_decode($body, true);
}
?>
--EXPECT--
