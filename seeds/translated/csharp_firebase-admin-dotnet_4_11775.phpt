--TEST--
Translated seed (inferredbugs-csharp: firebase-admin-dotnet/4)
--FILE--
<?php
$cases = [
    [400, 'InvalidArgument'],
    [401, 'Unauthenticated'],
    [403, 'PermissionDenied'],
    [404, 'NotFound'],
    [409, 'Conflict'],
    [429, 'ResourceExhausted'],
    [500, 'Internal'],
    [503, 'Unavailable'],
];

foreach ($cases as [$status, $expected]) {
    $json = '{}';
    $error = [
        'ErrorCode' => $expected,
        'Message' => "Unexpected HTTP response with status: $status ($status)\n$json",
        'HttpResponse' => ['StatusCode' => $status, 'ContentType' => 'application/json', 'Body' => $json],
        'InnerException' => null,
    ];
    $error['HttpResponse']['Body'] = $json;
    $error['HttpResponse']['StatusCode'] = $status;
    $error['HttpResponse']['ContentType'] = 'application/json';
    $error['Message'] = "Unexpected HTTP response with status: $status ($status)\n$json";
}

foreach ($cases as [$status, $expected]) {
    $text = 'not json';
    $error = [
        'ErrorCode' => $expected,
        'Message' => "Unexpected HTTP response with status: $status ($status)\n$text",
        'HttpResponse' => ['StatusCode' => $status, 'ContentType' => 'text/plain', 'Body' => $text],
        'InnerException' => null,
    ];
    $error['HttpResponse']['Body'] = $text;
    $error['HttpResponse']['StatusCode'] = $status;
    $error['HttpResponse']['ContentType'] = 'text/plain';
    $error['Message'] = "Unexpected HTTP response with status: $status ($status)\n$text";
}

$status = 405;
$json = '{}';
$error = [
    'ErrorCode' => 'Unknown',
    'Message' => "Unexpected HTTP response with status: $status (MethodNotAllowed)\n$json",
    'HttpResponse' => ['StatusCode' => $status, 'ContentType' => 'application/json', 'Body' => $json],
    'InnerException' => null,
];
$error['HttpResponse']['Body'] = $json;
$error['HttpResponse']['StatusCode'] = $status;
$error['HttpResponse']['ContentType'] = 'application/json';
$error['Message'] = "Unexpected HTTP response with status: $status (MethodNotAllowed)\n$json";
?>
--EXPECT--
