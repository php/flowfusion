--TEST--
Translated seed (inferredbugs-csharp: data-accelerator/1)
--FILE--
<?php
$base = "http://127.0.0.1:1";
$payload = ["command" => "x"];
$url = $base . "/api/1.2/commands/execute?language=scala&clusterId=" . rawurlencode((string)0) . "&contextId=" . rawurlencode((string)0);
$q = http_build_query($payload, "", "&", PHP_QUERY_RFC3986);
$u = $url . "&" . $q;
parse_str(parse_url($u, PHP_URL_QUERY) ?? "", $out);
$json1 = '{"id":"cmd-1"}';
$r1 = json_decode($json1, true);
$status = "Running";
$json2 = '{"id":"cmd-1","status":"Finished","results":{"resultType":"text","data":"ok","summary":null,"cause":null}}';
do {
    $resp = json_decode($json2, true);
    $status = $resp["status"];
} while ($status !== "Finished");
echo isset($resp["results"]["data"]) ? (string)$resp["results"]["data"] : "";
?>
--EXPECT--
