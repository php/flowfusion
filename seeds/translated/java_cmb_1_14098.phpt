--TEST--
Translated seed (inferredbugs-java: cmb/1)
--FILE--
<?php
$topicArn = "topicArn_example";
$userId = "userId_example";
$params = $_POST;

$numRetries = isset($params['numRetries']) ? (int)$params['numRetries'] : 0;
$retriesNoDelay = isset($params['retriesNoDelay']) ? (int)$params['retriesNoDelay'] : 0;
$minDelay = isset($params['minDelay']) ? (int)$params['minDelay'] : 0;
$minDelayRetries = isset($params['minDelayRetries']) ? (int)$params['minDelayRetries'] : 0;
$maxDelay = isset($params['maxDelay']) ? (int)$params['maxDelay'] : 0;
$maxDelayRetries = isset($params['maxDelayRetries']) ? (int)$params['maxDelayRetries'] : 0;
$maxReceiveRate = isset($params['maxReceiveRate']) ? (int)$params['maxReceiveRate'] : 0;

$ignoreOverride = isset($params['ignoreOverride']) ? true : false;
$backoffFunc = isset($params['backoffFunc']) ? $params['backoffFunc'] : "linear";

if ($maxDelay > 3600) $maxDelay = 3600; // Boundary condition
if ($minDelay > $maxDelay) $minDelay = $maxDelay; // Boundary condition

$deliveryPolicy = [
    'numRetries' => $numRetries,
    'retriesNoDelay' => $retriesNoDelay,
    'minDelay' => $minDelay,
    'minDelayRetries' => $minDelayRetries,
    'maxDelay' => $maxDelay,
    'maxDelayRetries' => $maxDelayRetries,
    'maxReceiveRate' => $maxReceiveRate,
    'backoffFunc' => $backoffFunc,
    'ignoreOverride' => $ignoreOverride
];

file_put_contents('deliveryPolicy.json', json_encode($deliveryPolicy)); // Simulates saving the policy

header('Content-Type: text/html');
echo "<html><body onload='window.close();'>Delivery policy updated.</body></html>";
?>
--EXPECT--
