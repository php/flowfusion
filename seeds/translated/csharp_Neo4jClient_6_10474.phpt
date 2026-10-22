--TEST--
Translated seed (inferredbugs-csharp: Neo4jClient/6)
--FILE--
<?php
$values = ["foo", "foo東京", "", null, "123", "bar", "baz", "text is too long"];
foreach ($values as $v) {
    $s = (string)$v;
    $len = strlen($s);
    $ok = ($len <= 4) && ($s === "" || preg_match('/^\w*$/u', $s));
    $required = ($v !== null);
    $payload = ["Foo" => $s, "Bar" => "bar", "Baz" => "baz"];
    $json = json_encode($payload, JSON_UNESCAPED_UNICODE);
    $decoded = json_decode($json, true);
    $id = 760;
    $node = ["id" => $id, "data" => $decoded];
    $rel = ["type" => "TEST_RELATIONSHIP", "start" => $id, "end" => 789, "data" => ["Foo" => 123, "Bar" => 456, "Baz" => 789]];
    $batch = [
        ["method" => "POST", "to" => "/node", "body" => $payload, "id" => 0],
        ["method" => "POST", "to" => "/node/789/relationships", "body" => $rel, "id" => 1],
    ];
    $batchJson = json_encode($batch, JSON_UNESCAPED_UNICODE);
    $roundTrip = json_decode($batchJson, true);
    if ($roundTrip[0]["body"]["Foo"] === "foo東京") {
        $node["unicode"] = true;
    }
    $node["ok"] = $ok && $required;
}
echo "";
?>
--EXPECT--
