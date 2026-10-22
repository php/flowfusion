--TEST--
Translated seed (inferredbugs-csharp: Simple.Web/2)
--FILE--
<?php
declare(strict_types=1);

class RootEndpoint {
    public function get(): int { return 200; }
    public function output(): string { return "<h1>Hello</h1>"; }
}

$accept = ["text/html"];
$method = "GET";
$url = "http://test.com/";

$endpoint = new RootEndpoint();
$status = $endpoint->get();
$contentType = in_array("text/html", $accept, true) ? "text/html" : "text/plain";
$body = $method === "GET" && $url === "http://test.com/" ? $endpoint->output() : "";

echo $status . "\n";
echo $contentType . "\n";
echo $body;
?>
--EXPECT--
