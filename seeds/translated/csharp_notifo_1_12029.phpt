--TEST--
Translated seed (inferredbugs-csharp: notifo/1)
--FILE--
<?php
$apiKey = "k";
$apiUrl = "https://app.notifo.io";
$timeout = 10;

if (trim($apiKey) === '') {
    throw new Exception("API Key not defined.");
}
if (trim($apiUrl) === '') {
    throw new Exception("API URL not defined.");
}
if (filter_var($apiUrl, FILTER_VALIDATE_URL) === false || !preg_match('~^https?://~i', $apiUrl)) {
    throw new Exception("API URL is not a well defined absolute URL.");
}

$headers = ["ApiKey: " . $apiKey];
$context = stream_context_create([
    "http" => [
        "timeout" => $timeout,
        "header" => implode("\r\n", $headers),
    ],
]);

$client = fopen($apiUrl, "r", false, $context);
if ($client !== false) {
    fclose($client);
}
?>
--EXPECT--
