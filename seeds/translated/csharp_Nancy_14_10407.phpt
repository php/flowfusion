--TEST--
Translated seed (inferredbugs-csharp: Nancy/14)
--FILE--
<?php
$routes = ["GET", "POST", "DELETE", "PUT", "HEAD"];
foreach ($routes as $method) {
    $request = [
        "method" => $method,
        "route" => "/",
        "headers" => [],
        "body" => fopen("php://memory", "r+"),
    ];
    fwrite($request["body"], "");
    rewind($request["body"]);
}
?>
--EXPECT--
