--TEST--
Translated seed (inferredbugs-csharp: core-grpc/3)
--FILE--
<?php
$lock = fopen('php://temp', 'r+');
$map = [];
$k = 'T';
if (!isset($map[$k])) {
    flock($lock, LOCK_EX);
    if (!isset($map[$k])) {
        $options = ['ConfigPath' => __FILE__, 'ServiceName' => '', 'Scheme' => '', 'GrpcChannelOptions' => null];
        $service = ['Name' => 'svc', 'Scheme' => 'http'];
        if ($options['ServiceName'] === '' || ctype_space($options['ServiceName'])) $options['ServiceName'] = $service['Name'];
        if ($options['Scheme'] === '' || ctype_space($options['Scheme'])) $options['Scheme'] = $service['Scheme'];
        if ($options['GrpcChannelOptions'] === null) $options['GrpcChannelOptions'] = ['ServiceConfig' => ['LoadBalancingConfigs' => []]];
        if (count($options['GrpcChannelOptions']['ServiceConfig']['LoadBalancingConfigs']) <= 0) $options['GrpcChannelOptions']['ServiceConfig']['LoadBalancingConfigs'][] = 'random';
        $map[$k] = $options['Scheme'] !== '' ? $options['Scheme'] . ':///' . $options['ServiceName'] : 'internal:///' . json_encode($options);
    }
    flock($lock, LOCK_UN);
}
echo $map[$k], PHP_EOL;
--EXPECT--
