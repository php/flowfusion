--TEST--
Translated seed (inferredbugs-csharp: RestSharp/4)
--FILE--
<?php
$baseUrl = "http://example.com/";
if (str_ends_with($baseUrl, "/")) {
    $baseUrl = substr($baseUrl, 0, -1);
}

$handlers = [
    "application/json" => "json",
    "application/xml" => "xml",
    "text/json" => "json",
    "text/x-json" => "json",
    "text/javascript" => "json",
    "text/xml" => "xml",
    "*" => "xml",
];

$request = [
    "Resource" => "/api/{id}/items/",
    "Method" => "GET",
    "Parameters" => [
        ["Name" => "id", "Value" => "a/b?c=d", "Type" => "UrlSegment"],
        ["Name" => "q", "Value" => "x y&z", "Type" => "GetOrPost"],
        ["Name" => "h", "Value" => "v", "Type" => "HttpHeader"],
        ["Name" => "c", "Value" => "cookie", "Type" => "Cookie"],
        ["Name" => "body", "Value" => "ignored", "Type" => "RequestBody"],
    ],
];

$assembled = $request["Resource"];
foreach ($request["Parameters"] as $p) {
    if ($p["Type"] === "UrlSegment") {
        $assembled = str_replace("{" . $p["Name"] . "}", rawurlencode((string)$p["Value"]), $assembled);
    }
}
if ($assembled !== "" && $assembled[0] === "/") {
    $assembled = substr($assembled, 1);
}
if ($baseUrl !== "") {
    $assembled = $baseUrl . "/" . $assembled;
}
if ($request["Method"] !== "POST" && $request["Method"] !== "PUT") {
    $qp = [];
    foreach ($request["Parameters"] as $p) {
        if ($p["Type"] === "GetOrPost" && $p["Value"] !== null) {
            $qp[] = rawurlencode($p["Name"]) . "=" . rawurlencode((string)$p["Value"]);
        }
    }
    if ($qp) {
        if (str_ends_with($assembled, "/")) {
            $assembled = substr($assembled, 0, -1);
        }
        $assembled .= "?" . implode("&", $qp);
    }
}

$uri = $assembled;
$contentType = "text/xml; charset=utf-8";
$semi = strpos($contentType, ";");
if ($semi !== false) {
    $contentType = substr($contentType, 0, $semi);
}
$handler = $handlers[$contentType] ?? $handlers["*"];

echo $uri . "\n";
echo $handler . "\n";
?>
--EXPECT--
