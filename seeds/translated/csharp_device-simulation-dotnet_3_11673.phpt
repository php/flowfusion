--TEST--
Translated seed (inferredbugs-csharp: device-simulation-dotnet/3)
--FILE--
<?php
$endpoint = "http://localhost/diagnostics";
$callerName = __FUNCTION__;
$filePath = __FILE__;
$lineNumber = __LINE__;
$events = [];

$events[] = ["EventType" => "ServiceStart" . "boot", "EventProperties" => null];
$events[] = ["EventType" => "ServiceHeartbeat", "EventProperties" => null];

$props = ["message" => "error(CallerMemberName = '$callerName', CallerFilePath = '$filePath', lineNumber = '$lineNumber')"];
$exceptionMessage = "";
$data = ["x" => 1, "y" => null, "z" => [1, 2, 3]];
if ($exceptionMessage !== "") $props["ExceptionMessage"] = $exceptionMessage;
if ($data !== null) $props["data"] = $data;
$events[] = ["EventType" => "ServiceError", "EventProperties" => $props];

$props = ["message" => "error2(CallerMemberName = '$callerName', CallerFilePath = '$filePath', lineNumber = '$lineNumber')"];
$exceptionMessage = "boom";
$data = null;
if ($exceptionMessage !== "") $props["ExceptionMessage"] = $exceptionMessage;
if ($data !== null) $props["data"] = $data;
$events[] = ["EventType" => "ServiceError", "EventProperties" => $props];

foreach ($events as $event) {
    json_encode($event, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}
?>
--EXPECT--
