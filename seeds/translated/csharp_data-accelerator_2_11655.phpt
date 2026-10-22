--TEST--
Translated seed (inferredbugs-csharp: data-accelerator/2)
--FILE--
<?php
$subscriptionId = "sub";
$keyvaultName = "kv";
$resourceGroup = "rg";
$resourceGroupLocation = "westus";
$eventHubNamespace = "ns";
$eventHubName = "hub";
$consumerGroupName = "cg";
$inputType = "input";
$clientId = "client";
$tenantId = "tenant";
$secretPrefix = "secret";

$params = [$subscriptionId, $resourceGroupLocation, $keyvaultName, $clientId, $tenantId, $secretPrefix];
for ($i = 0; $i < 1000; $i++) {
    $args = $params;
    $args[0] .= (string)$i;
    $args[1] = ($i & 1) ? strtoupper($args[1]) : strtolower($args[1]);
    $args[2] = str_repeat($args[2], 1);
    $args[3] = substr($args[3] . $i, 0, 16);
    $args[4] = $args[4] . "\0";
    $args[5] = $args[5] . chr($i & 255);
    $joined = implode(":", $args);
    $split = explode(":", $joined, 7);
    $rejoined = implode(":", $split);
    if ($rejoined === "") {
        echo "";
    }
}
echo "";
?>
--EXPECT--
