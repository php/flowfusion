--TEST--
Translated seed (inferredbugs-java: acs-aem-commons/16)
--FILE--
<?php

header('Content-Type: application/json; charset=UTF-8');

$dateFormat = 'YdHmS';
$today = new DateTime();
$filename = $today->format($dateFormat);
header('Content-Disposition: attachment; filename="jcr-checksum-' . $filename . '.json"');

$optionsName = $_GET['options_name'] ?? null;

if (empty($optionsName)) {
    http_response_code(400);
    echo json_encode(["error" => "ERROR: At least one path must be specified"]);
    exit;
}

$paths = explode(',', $_GET['paths'] ?? '');

if (empty($paths)) {
    http_response_code(400);
    echo json_encode(["error" => "ERROR: At least one path must be specified"]);
    exit;
}

session_start();
$session = $_SESSION['repository_session'] ?? null;

if (!$session) {
    http_response_code(400);
    echo json_encode(["error" => "ERROR: No valid session"]);
    exit;
}

$jsonWriter = fopen('php://output', 'w');
fwrite($jsonWriter, json_encode(["paths" => $paths, "options" => $optionsName]));
fclose($jsonWriter);
--EXPECT--
