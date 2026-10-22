--TEST--
Translated seed (inferredbugs-java: cmb/3)
--FILE--
<?php
$endpoint = "http://example.com"; 
$message = "test message"; 

if ($message === null || $endpoint === null) {
    throw new Exception("Message and Endpoint must both be set");
}

$options = [
    "http" => [
        "header"  => "Content-type: application/x-www-form-urlencoded\r\n",
        "method"  => "POST",
        "content" => http_build_query(['message' => $message]),
        "timeout" => 5,
    ],
];

$context  = stream_context_create($options);
$response = @file_get_contents($endpoint, false, $context);

if ($response === FALSE) {
    $error = error_get_last();
    echo "Error occurred: {$error['message']}\n";
} else {
    echo "Response received: $response";
}
--EXPECT--
