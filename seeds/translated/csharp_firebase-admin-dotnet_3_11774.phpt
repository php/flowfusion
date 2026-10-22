--TEST--
Translated seed (inferredbugs-csharp: firebase-admin-dotnet/3)
--FILE--
<?php
$jsons = [
    "{\n        \"error\": {\n            \"status\": \"UNAVAILABLE\",\n            \"message\": \"Test error message\"\n        }\n    }",
    "not json",
    "{\n        \"error\": {\n            \"message\": \"Test error message\"\n        }\n    }",
    "{\n        \"error\": {\n            \"status\": \"INVALID_ARGUMENT\",\n        }\n    }",
    "{}",
];

foreach ($jsons as $json) {
    $resp = (object)[
        'statusCode' => 503,
        'content' => $json,
        'contentType' => str_starts_with(ltrim($json), '{') ? 'application/json' : 'text/plain',
    ];

    $data = json_decode($json, true);
    $code = 'Unavailable';
    $message = "Unexpected HTTP response with status: 503 (ServiceUnavailable)\n$json";

    if (is_array($data) && isset($data['error']) && is_array($data['error'])) {
        if (($data['error']['status'] ?? null) === 'UNAVAILABLE') $code = 'Unavailable';
        elseif (($data['error']['status'] ?? null) === 'INVALID_ARGUMENT') $code = 'InvalidArgument';
        if (isset($data['error']['message']) && is_string($data['error']['message'])) $message = $data['error']['message'];
    }

    echo $code . "\n" . $message . "\n";
}
?>
--EXPECT--
