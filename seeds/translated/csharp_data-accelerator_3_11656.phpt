--TEST--
Translated seed (inferredbugs-csharp: data-accelerator/3)
--FILE--
<?php
$subscriptionId = "sub";
$keyvaultName = "kv";
$resourceGroup = "rg";
$resourceGroupLocation = "westus";
$eventHubNamespace = "ns";
$eventHubName = "eh";
$consumerGroupName = "cg";
$inputType = "input";
$clientId = "client";
$tenantId = "tenant";
$secretPrefix = "secret";

$params = [$subscriptionId, $resourceGroupLocation, $keyvaultName, $clientId, $tenantId, $secretPrefix];
$resource = implode(":", $params);

$create = [$resourceGroup, $eventHubNamespace, $eventHubName, $consumerGroupName, $inputType];
$delete = [$resourceGroup, $eventHubNamespace, $eventHubName, $consumerGroupName, $inputType];

$state = [];
$state["create"] = hash("sha256", implode("|", array_merge([$resource], $create)));
$state["delete"] = hash("sha256", implode("|", array_merge([$resource], $delete)));

echo $state["create"] . "\n";
echo $state["delete"] . "\n";
?>
--EXPECT--
