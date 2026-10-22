--TEST--
Translated seed (inferredbugs-csharp: FunctionMonkey/2)
--FILE--
<?php
$body = stream_get_contents(STDIN);
$data = json_decode($body, true);
$name = $_GET['name'] ?? ($data['name'] ?? null);

if ($name !== null) {
    echo json_encode(['message' => 'Hello world'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), PHP_EOL;
} else {
    echo json_encode(['error' => 'Please pass a name on the query string or in the request body'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), PHP_EOL;
}
?>
--EXPECT--
