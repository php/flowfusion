--TEST--
Translated seed (inferredbugs-csharp: sdk-csharp/1)
--FILE--
<?php
$headers = [
    'ce_specversion' => "1.0",
    'content-type' => "application/cloudevents+json; charset=utf-8",
    'ce_id' => "123",
    'ce_type' => "demo",
    'ce_source' => "/test",
    'ce_partitionkey' => "k1",
    'ce_extra' => "x",
];

$hasCloudEvent = array_key_exists('ce_specversion', $headers) ||
    (isset($headers['content-type']) && stripos($headers['content-type'], 'application/cloudevents') === 0);

if ($hasCloudEvent) {
    $contentType = $headers['content-type'] ?? null;

    if ($contentType !== null && stripos($contentType, 'application/cloudevents') === 0) {
        $value = '{"specversion":"1.0","id":"123","type":"demo","source":"/test"}';
        $event = json_decode($value, true);
        $event['data'] = $value;
        $event['datacontenttype'] = $contentType;
    } else {
        $versionId = $headers['ce_specversion'] ?? null;
        if ($versionId !== "1.0") {
            exit(0);
        }

        $event = [
            'specversion' => $versionId,
            'data' => "payload",
            'datacontenttype' => $contentType,
        ];

        foreach ($headers as $name => $headerValue) {
            if (stripos($name, 'ce_') !== 0) {
                continue;
            }
            $attr = strtolower(substr($name, 3));
            if ($attr === 'specversion') {
                continue;
            }
            $event[$attr] = $headerValue;
        }
    }

    if (!empty($headers['ce_partitionkey'])) {
        $event['partitionkey'] = $headers['ce_partitionkey'];
    }
}

echo "ok\n";
--EXPECT--
