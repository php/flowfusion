--TEST--
Translated seed (inferredbugs-csharp: influxdb-client-csharp/1)
--FILE--
<?php
$query = "from(bucket:\"b\") |> range(start: -1h)";
$dialect = [
    "header" => true,
    "delimiter" => ",",
    "quoteChar" => "\"",
    "commentPrefix" => "#",
    "annotations" => ["datatype", "group", "default"],
];

$body = json_encode(["query" => $query, "dialect" => $dialect], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
if ($body === false) {
    exit(1);
}

$ping = ["method" => "GET", "uri" => "ping"];
$queryReq = ["method" => "POST", "uri" => "v2/query", "content" => $query];

echo $body, "\n";
echo json_encode($ping, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), "\n";
echo json_encode($queryReq, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), "\n";
?>
--EXPECT--
