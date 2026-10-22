--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/82)
--FILE--
<?php
$labels = [];
$errors = [];

function setLabel(array &$target, string $name, $value): void {
    $target[$name] = $value;
}

function serializeItem(array $item): string {
    return json_encode($item, JSON_PRESERVE_ZERO_FRACTION);
}

$values = ["StrValue", 123, 234.0, 346, true, false, 457.0];

foreach ($values as $v) {
    $tx = ["tags" => []];
    setLabel($tx["tags"], "myLabel", $v);
    serializeItem($tx);
}

$tx = ["tags" => []];
$span = ["tags" => []];

setLabel($span["tags"], "intLabel", 1);
setLabel($span["tags"], "stringLabel", "abc");
setLabel($span["tags"], "boolLabel", true);
serializeItem($span);

setLabel($tx["tags"], "intLabel", 1);
setLabel($tx["tags"], "stringLabel", "abc");
setLabel($tx["tags"], "boolLabel", true);
serializeItem($tx);

$tx = ["tags" => []];
setLabel($tx["tags"], "intLabel", 5);
$errors[] = ["context" => ["tags" => $tx["tags"]], "message" => "Test Error", "labels" => ["stringLabel" => "test"]];
setLabel($tx["tags"], "boolLabel", true);

$tx = ["tags" => []];
setLabel($tx["tags"], "intLabel", 5);
setLabel($tx["tags"], "intLabel", 6);

$tx = ["tags" => []];
$tx["labels"] = [];
$tx["labels"]["foo1"] = "bar1";
$tx["tags"]["foo1"] = "bar1";
serializeItem($tx);
$span = ["tags" => [], "labels" => []];
$span["labels"]["foo2"] = "bar2";
$span["tags"]["foo2"] = "bar2";
serializeItem($span);

$transaction = ["tags" => [], "labels" => []];
$transaction["labels"]["foo"] = "bar";
$transaction["tags"]["foo"] = "bar";
$transaction["tags"]["item2"] = 123;
unset($transaction["labels"]["foo"]);

$transaction = ["tags" => [], "labels" => []];
$transaction["labels"]["foo"] = "bar";
$transaction["tags"]["foo"] = "bar";
$transaction["tags"]["intItem"] = 42;
unset($transaction["labels"]["foo"]);
serializeItem($transaction);
?>
--EXPECT--
