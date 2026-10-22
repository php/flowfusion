--TEST--
Translated seed (inferredbugs-csharp: dotnet-standard-sdk/1)
--FILE--
<?php
$filter = "(language::en,request.context.metadata.deployment::deployment_1)";
$cases = [
    ["Turn on the winshield wipers", true],
    ["", false],
    ["0", true],
    ["false", true],
];
$json = json_encode($cases, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
$data = json_decode($json, true);
foreach ($data as $item) {
    $text = $item[0];
    $alt = $item[1];
    $payload = [
        "input" => ["text" => $text],
        "alternate_intents" => $alt,
        "filter" => $filter,
    ];
    $encoded = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $decoded = json_decode($encoded, true);
    if (!is_array($decoded) || !array_key_exists("input", $decoded) || !array_key_exists("alternate_intents", $decoded)) {
        exit(1);
    }
}
echo "";
?>
--EXPECT--
