--TEST--
Translated seed (inferredbugs-csharp: jaeger-client-csharp/1)
--FILE--
<?php
$buffer = [];
$bufferSize = 10;

$span = [
    'context' => ['traceId' => '1', 'parentId' => '0', 'spanId' => '2'],
    'tracer' => ['serviceName' => 'svc', 'hostIPv4' => '127.0.0.1'],
    'logs' => [
        ['timestamp' => 0, 'message' => null, 'fields' => ['a' => 1]],
        ['timestamp' => 1, 'message' => 'm', 'fields' => ['b' => 2]],
    ],
    'tags' => [
        ['key' => 'span.kind', 'value' => 'server'],
        ['key' => 'http.method', 'value' => 'GET'],
        ['key' => 'empty', 'value' => ''],
    ],
    'operationName' => 'TeStOp',
    'startTimestamp' => 100,
    'finishTimestamp' => 250,
];

for ($i = 0; $i < 11; $i++) {
    $annotations = [];
    foreach ($span['logs'] as $log) {
        $annotations[] = [
            'timestamp' => $log['timestamp'],
            'value' => $log['message'] ?? json_encode($log['fields']),
        ];
    }

    $tags = [];
    $kind = '';
    foreach ($span['tags'] as $t) {
        if ($t['key'] === 'span.kind') {
            $kind = $t['value'];
        } else {
            $tags[$t['key']] = $t['value'];
        }
    }

    $buffer[] = [
        'traceId' => (string)$span['context']['traceId'],
        'name' => strtolower($span['operationName']),
        'parentId' => (string)$span['context']['parentId'],
        'id' => (string)$span['context']['spanId'],
        'timestamp' => $span['startTimestamp'],
        'duration' => $span['finishTimestamp'] - $span['startTimestamp'],
        'localEndpoint' => [
            'serviceName' => $span['tracer']['serviceName'],
            'ipv4' => $span['tracer']['hostIPv4'],
        ],
        'annotations' => $annotations,
        'tags' => $tags,
        'kind' => $kind === 'client' ? 'CLIENT' : ($kind === 'consumer' ? 'CONSUMER' : ($kind === 'producer' ? 'PRODUCER' : ($kind === 'server' ? 'SERVER' : 0))),
    ];

    if (count($buffer) > $bufferSize) {
        json_encode($buffer);
        $buffer = [];
    }
}

json_encode($buffer);
?>
--EXPECT--
