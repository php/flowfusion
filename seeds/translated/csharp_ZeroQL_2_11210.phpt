--TEST--
Translated seed (inferredbugs-csharp: ZeroQL/2)
--FILE--
<?php
$items = [];
for ($i = 0; $i < 3; $i++) {
    $items[] = ['kind' => ($i % 2 === 0 ? 'Query' : 'Mutation'), 'args' => [$i, "v$i"]];
}
foreach ($items as $call) {
    $name = strtolower($call['kind']);
    $last = $call['args'][count($call['args']) - 1];
    $key = json_encode($call['args']);
    $payload = $name . ' ' . ($last !== '' ? $last : '') . $key;
    $request = ['Variables' => $call['args'], 'Query' => $payload];
    $json = json_encode($request);
    $response = json_decode($json, true);
    if ($response === null) {
        echo "Failed";
        continue;
    }
    if (!empty($response['Errors'])) {
        continue;
    }
    $result = ['Query' => $payload, 'Data' => $response['Query'] ?? null];
    unset($result);
}
?>
--EXPECT--
