--TEST--
Translated seed (inferredbugs-csharp: Neo4jClient/7)
--FILE--
<?php
$values = [
    ["Foo" => "foo", "Bar" => "bar", "Baz" => "baz"],
    ["Foo" => "foo東京", "Bar" => "bar", "Baz" => "baz"],
    ["Foo" => "text is too long", "Bar" => null, "Baz" => "123"],
    ["Foo" => "foo", "Bar" => "bar", "Baz" => "baz", "Extra" => ["k" => "v"]],
];

foreach ($values as $v) {
    $json = json_encode($v, JSON_UNESCAPED_UNICODE);
    $decoded = json_decode($json, true);
    if (!is_array($decoded)) {
        continue;
    }
    if (array_key_exists("Foo", $decoded) && is_string($decoded["Foo"])) {
        $decoded["Foo"] = substr($decoded["Foo"], 0, 4);
    }
    if (array_key_exists("Bar", $decoded) && $decoded["Bar"] === null) {
        $decoded["Bar"] = "";
    }
    if (array_key_exists("Baz", $decoded) && is_string($decoded["Baz"])) {
        preg_match('/^\w*$/u', $decoded["Baz"]);
    }
    $roundtrip = json_encode($decoded, JSON_UNESCAPED_UNICODE);
    json_decode($roundtrip, true);
}

$node = ["id" => 760, "data" => ["Foo" => "foo", "Bar" => "bar", "Baz" => "baz"]];
$rel = ["type" => "TEST_RELATIONSHIP", "start" => 760, "end" => 789, "data" => ["Foo" => "123", "Bar" => "456", "Baz" => "789"]];
$batch = [$node, $rel, ["index" => "my_index", "key" => "key", "value" => "value"], ["index" => "my_index", "key" => "key3", "value" => "value3"]];
json_encode($batch, JSON_UNESCAPED_UNICODE);
json_decode(json_encode($batch, JSON_UNESCAPED_UNICODE), true);
?>
--EXPECT--
