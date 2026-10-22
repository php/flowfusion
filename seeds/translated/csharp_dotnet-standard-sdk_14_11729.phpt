--TEST--
Translated seed (inferredbugs-csharp: dotnet-standard-sdk/14)
--FILE--
<?php
$p = "a/b c?d#e&f";
$q = rawurlencode($p);
$u = "/v2/projects/$q/collections/" . rawurlencode("x/y:z") . "/documents/" . rawurlencode("id+1");
$b = [
    "name" => "n",
    "description" => "",
    "language" => null,
    "collection_ids" => ["c1", "c2"],
    "count" => 0,
    "offset" => 10000,
    "highlight" => false,
    "spelling_suggestions" => true,
    "table_results" => ["enabled" => true],
    "suggested_refinements" => ["max" => 3],
    "passages" => ["enabled" => false],
];
$j = json_encode(array_filter($b, fn($v) => $v !== null && $v !== ""), JSON_UNESCAPED_SLASHES);
$m = http_build_query([
    "version" => "2021-05-13",
    "prefix" => "Ho",
    "collection_ids" => implode(",", ["a", "b,c", "d"]),
    "count" => 0,
], "", "&", PHP_QUERY_RFC3986);
$f = tempnam(sys_get_temp_dir(), "phpbug");
file_put_contents($f, str_repeat("x", 3) . "\n" . $u . "\n" . $j . "\n" . $m);
$fp = fopen($f, "rb");
stream_get_contents($fp);
fclose($fp);
unlink($f);
echo $u, "\n", $j, "\n", $m, "\n";
--EXPECT--
