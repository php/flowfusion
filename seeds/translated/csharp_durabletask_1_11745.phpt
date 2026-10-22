--TEST--
Translated seed (inferredbugs-csharp: durabletask/1)
--FILE--
<?php
$address = $argv[1] ?? 'http://127.0.0.1:8080';
if ($address === null) {
    throw new InvalidArgumentException('listeningAddress');
}

$provider = new stdClass();
$provider->OrchestrationServiceClient = new stdClass();

$services = [];
$services['IOrchestrationServiceClient'] = $provider->OrchestrationServiceClient;
$services['FabricOrchestrationServiceController'] = new stdClass();
$services['IExceptionLogger'] = new stdClass();
$services['IExceptionHandler'] = new stdClass();

ini_set('default_socket_timeout', '256');
ini_set('serialize_precision', '-1');

$config = [
    'routes' => [],
    'services' => $services,
    'include_error_detail' => true,
    'formatters' => [
        'xml' => false,
        'form_urlencoded' => false,
        'json_type_name_handling' => 'all',
    ],
];

echo $address, "\n";
echo count($config['services']), "\n";
echo ($config['include_error_detail'] ? '1' : '0'), "\n";
--EXPECT--
