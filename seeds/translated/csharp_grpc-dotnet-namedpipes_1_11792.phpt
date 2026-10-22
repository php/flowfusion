--TEST--
Translated seed (inferredbugs-csharp: grpc-dotnet-namedpipes/1)
--FILE--
<?php
$deadlineExpired = false;
$canceled = false;
$callStatusCode = 'OK';
$callStatusDetail = '';
$responseTrailers = [];
$responsePayload = null;

$methodHandlers = [
    'test' => function () use (&$deadlineExpired, &$canceled, &$callStatusCode, &$callStatusDetail, &$responseTrailers, &$responsePayload) {
        if ($deadlineExpired) {
            $statusCode = 'DeadlineExceeded';
            $statusDetail = '';
        } elseif ($canceled) {
            $statusCode = 'Cancelled';
            $statusDetail = '';
        } elseif ($callStatusCode !== 'OK') {
            $statusCode = $callStatusCode;
            $statusDetail = $callStatusDetail;
        } elseif ($responsePayload !== null) {
            $statusCode = 'OK';
            $statusDetail = '';
        } else {
            $statusCode = 'OK';
            $statusDetail = '';
        }
        $responseTrailers[] = [$statusCode, $statusDetail];
    }
];

$headers = ['content-type' => 'application/grpc'];
$payloadQueue = [];
$payloadQueue[] = "abc";
$payloadQueue[] = "";
$payloadQueue[] = str_repeat("x", 1);

$deadlineExpired = true;
$methodHandlers['test']();
$deadlineExpired = false;
$canceled = true;
$methodHandlers['test']();
$canceled = false;
$callStatusCode = 'Internal';
$callStatusDetail = 'handler error';
$methodHandlers['test']();
$callStatusCode = 'OK';
$callStatusDetail = '';
$responsePayload = "resp";
$methodHandlers['test']();

echo "done\n";
--EXPECT--
