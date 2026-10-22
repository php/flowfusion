--TEST--
Translated seed (inferredbugs-java: acme4j/19)
--FILE--
<?php

$url = "https://self-signed.badssl.com/";
$context = stream_context_create([
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ],
]);

$response = file_get_contents($url, false, $context);

if ($response === false) {
    echo "Connection failed";
} else {
    echo "Connection successful";
}

?>
--EXPECT--
