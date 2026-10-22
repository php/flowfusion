--TEST--
Translated seed (inferredbugs-csharp: azure-signalr/5)
--FILE--
<?php
$code = 200;
$content = "ok";
$callback = null;

$request = ["method" => "GET", "uri" => "/"];
$cancellationToken = false;

$response = ["status" => $code];
if ($content !== null) {
    $response["content"] = $content;
}
if ($callback !== null) {
    $callback($request, $cancellationToken);
}

echo $response["status"], "\n";
if (isset($response["content"])) {
    echo $response["content"], "\n";
}
?>
--EXPECT--
