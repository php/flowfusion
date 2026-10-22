--TEST--
Translated seed (inferredbugs-java: acme4j/9)
--FILE--
<?php
$url = "http://example.com/api/nonce";
$headers = get_headers($url, 1);
$responseCode = http_response_code();

if ($responseCode != 200 && $responseCode != 204) {
    throw new Exception("Fetching a nonce returned $responseCode " . $headers[0]);
}

$nonceHeader = $headers["Replay-Nonce"] ?? null;
if ($nonceHeader === null || trim($nonceHeader) === "") {
    throw new Exception("Server did not provide a nonce");
}

if (!preg_match('/^[0-9A-Za-z_-]+$/', $nonceHeader)) {
    throw new Exception("Invalid replay nonce: $nonceHeader");
}

$nonce = base64_decode($nonceHeader);
if ($nonce === false) {
    throw new Exception("Failed to decode nonce");
}

$payload = json_encode(['key' => 'value']);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Accept: application/json",
    "Content-Type: application/jose+json",
    "Replay-Nonce: " . base64_encode($nonce)
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

$response = curl_exec($ch);
$responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($responseCode < 200 || $responseCode >= 300) {
    throw new Exception("HTTP request failed with code: $responseCode");
}
--EXPECT--
