--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/42)
--FILE--
<?php
$txName = "TestTransaction";
$txType = "UnitTest";
$spanName = "TestSpan";
$exceptionMessage = "Foo!";
$agent = (object)["transactions" => [], "spans" => [], "errors" => [], "current" => null];

$agent->current = (object)[
    "name" => $txName,
    "type" => $txType,
    "result" => null,
    "tags" => [],
    "spans" => [],
    "errors" => [],
    "start" => microtime(true),
    "ended" => false
];

$agent->current->tags["fooTransaction1"] = "barTransaction1";
$agent->current->tags["fooTransaction2"] = "barTransaction2";

$span = (object)[
    "name" => $spanName,
    "type" => "external",
    "subtype" => null,
    "action" => null,
    "tags" => [],
    "errors" => [],
    "start" => microtime(true),
    "ended" => false
];
$span->tags["fooSpan1"] = "barSpan1";
$span->tags["fooSpan2"] = "barSpan2";
$agent->current->spans[] = $span;

usleep(5000);

try {
    throw new InvalidArgumentException($exceptionMessage);
} catch (Throwable $e) {
    $span->errors[] = $e->getMessage();
    $agent->current->errors[] = ["message" => $e->getMessage(), "culprit" => "PublicAPI-CaptureException"];
}

$span->ended = true;
$span->duration = (int)max(0, (microtime(true) - $span->start) * 1000);
$agent->spans[] = $span;

$agent->current->ended = true;
$agent->current->duration = (int)max(0, (microtime(true) - $agent->current->start) * 1000);
$agent->transactions[] = $agent->current;

$payload = [
    "service" => (object)[],
    "transactions" => [[
        "name" => $agent->current->name,
        "type" => $agent->current->type,
        "result" => $agent->current->result,
        "duration" => $agent->current->duration,
        "tags" => $agent->current->tags
    ]],
    "spans" => [[
        "name" => $span->name,
        "type" => $span->type,
        "subtype" => $span->subtype,
        "action" => $span->action,
        "duration" => $span->duration,
        "tags" => $span->tags
    ]],
    "errors" => [[
        "message" => $exceptionMessage,
        "culprit" => "PublicAPI-CaptureException"
    ]]
];

echo "";
?>
--EXPECT--
