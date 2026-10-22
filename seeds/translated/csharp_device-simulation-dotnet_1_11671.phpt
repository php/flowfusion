--TEST--
Translated seed (inferredbugs-csharp: device-simulation-dotnet/1)
--FILE--
<?php
$endpoint = "http://127.0.0.1/";
$callerName = __FUNCTION__;
$filePath = __FILE__;
$lineNumber = __LINE__;

foreach ([
    ["ServiceStart", null, "boot"],
    ["ServiceHeartbeat", null, ""],
    ["ServiceError", ["message" => "x", "ExceptionMessage" => "e", "data" => ["k" => "v"]], "err"],
    ["ServiceError", ["message" => "y"], "err2"],
] as $item) {
    [$eventType, $props, $suffix] = $item;
    $payload = ["EventType" => $eventType . $suffix, "EventProperties" => $props];
    if ($eventType === "ServiceError" && is_array($payload["EventProperties"])) {
        $payload["EventProperties"]["message"] .= "(CallerMemberName = '" . $callerName . "', CallerFilePath = '" . $filePath . "', lineNumber = '" . $lineNumber . "')";
    }
    json_encode($payload, JSON_UNESCAPED_SLASHES);
}
echo "";
?>
--EXPECT--
