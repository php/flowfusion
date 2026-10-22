--TEST--
Translated seed (inferredbugs-java: acme4j/8)
--FILE--
<?php
function sendRequest($url, $locale) {
    $headers = [
        "Accept: application/json",
        "Accept-Charset: utf-8",
        "Accept-Language: $locale",
    ];
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    $response = curl_exec($ch);
    
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headers = substr($response, 0, $header_size);
    $body = substr($response, $header_size);
    
    curl_close($ch);
    
    $response_code = http_response_code();
    if ($response_code !== 200 && $response_code !== 204) {
        throw new Exception("Error: $response_code $body");
    }
    
    return $body;
}

function readJsonResponse($response) {
    $json = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON response");
    }
    return $json;
}

function main() {
    $url = "https://example.com";
    $locale = "en-US";
    
    try {
        $response = sendRequest($url, $locale);
        $json = readJsonResponse($response);
        var_dump($json); // Display the JSON response for verification
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

main();
--EXPECT--
