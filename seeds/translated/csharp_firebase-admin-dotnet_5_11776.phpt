--TEST--
Translated seed (inferredbugs-csharp: firebase-admin-dotnet/5)
--FILE--
<?php
$map = [
    400 => 'InvalidArgument',
    401 => 'Unauthenticated',
    403 => 'PermissionDenied',
    404 => 'NotFound',
    409 => 'Conflict',
    429 => 'ResourceExhausted',
    500 => 'Internal',
    503 => 'Unavailable',
];

foreach ($map as $status => $expected) {
    foreach (["{}", "not json"] as $body) {
        $errorCode = $map[$status] ?? 'Unknown';
        $message = "Unexpected HTTP response with status: $status ($status)\n$body";
        $resp = ['status' => $status, 'body' => $body, 'content-type' => $body === '{}' ? 'application/json' : 'text/plain'];
        if ($errorCode !== $expected || $message === '' || $resp['status'] !== $status) {
            throw new Exception('unexpected');
        }
    }
}

$status = 405;
$body = '{}';
$errorCode = $map[$status] ?? 'Unknown';
$message = "Unexpected HTTP response with status: $status (MethodNotAllowed)\n$body";
$resp = ['status' => $status, 'body' => $body, 'content-type' => 'application/json'];
if ($errorCode !== 'Unknown' || $message === '' || $resp['status'] !== $status) {
    throw new Exception('unexpected');
}
?>
--EXPECT--
