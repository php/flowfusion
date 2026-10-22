--TEST--
Translated seed (inferredbugs-csharp: firebase-admin-dotnet/6)
--FILE--
<?php
$codes = [
    [400, 'InvalidArgument'],
    [401, 'Unauthenticated'],
    [403, 'PermissionDenied'],
    [404, 'NotFound'],
    [409, 'Conflict'],
    [429, 'ResourceExhausted'],
    [500, 'Internal'],
    [503, 'Unavailable'],
];

foreach ($codes as [$status, $expected]) {
    foreach (["{}", "not json"] as $body) {
        $message = "Unexpected HTTP response with status: $status (" . ($status === 429 ? 'TooManyRequests' : ($status === 400 ? 'BadRequest' : ($status === 401 ? 'Unauthorized' : ($status === 403 ? 'Forbidden' : ($status === 404 ? 'NotFound' : ($status === 409 ? 'Conflict' : ($status === 500 ? 'InternalServerError' : 'ServiceUnavailable'))))))) . ")\n$body";
        $error = [
            'ErrorCode' => $expected,
            'Message' => $message,
            'HttpResponse' => ['StatusCode' => $status, 'Body' => $body],
            'InnerException' => null,
        ];
        $error['ErrorCode'];
        $error['Message'];
        $error['HttpResponse'];
        $error['InnerException'];
    }
}

$status = 405;
$body = "{}";
$message = "Unexpected HTTP response with status: $status (MethodNotAllowed)\n$body";
$error = [
    'ErrorCode' => 'Unknown',
    'Message' => $message,
    'HttpResponse' => ['StatusCode' => $status, 'Body' => $body],
    'InnerException' => null,
];
$error['ErrorCode'];
$error['Message'];
$error['HttpResponse'];
$error['InnerException'];
?>
--EXPECT--
