--TEST--
Translated seed (inferredbugs-java: SpringCloud/1)
--FILE--
<?php

$xClientTokenUser = "x-client-token-user";
$xClientToken = "x-client-token";
$bearer = "Bearer";

$requestHeaders = getallheaders();
$authentication = isset($requestHeaders['Authorization']) ? $requestHeaders['Authorization'] : '';
$method = $_SERVER['REQUEST_METHOD'];
$url = $_SERVER['REQUEST_URI'];

if (ignoreAuthentication($url)) {
    exit; // Continue to the next filter
}

if (empty($authentication) || strpos($authentication, $bearer) !== 0) {
    http_response_code(401);
    exit("Unauthorized");
}

if (hasPermission($authentication, $url, $method)) {
    header($xClientToken . ": TODO add service authentication");
    // Assuming getJwt returns claims as a string for simplicity
    header($xClientTokenUser . ": " . getJwt($authentication));
    exit; // Continue to the next filter
}

http_response_code(401);
exit("Unauthorized");

function ignoreAuthentication($url) {
    // Simulate ignore authentication logic
    return false; // Change as necessary
}

function hasPermission($authentication, $url, $method) {
    // Simulate permission checking logic
    return true; // Change as necessary
}

function getJwt($authentication) {
    // Simulate JWT claims retrieval
    return json_encode(["claims" => "user_data"]); // Change as necessary
}
--EXPECT--
