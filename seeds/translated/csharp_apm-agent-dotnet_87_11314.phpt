--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/87)
--FILE--
<?php
$labels = [];
$labels["myLabel"] = "StrValue";
$labels["myLabel"] = 123;
$labels["myLabel"] = 234.0;
$labels["myLabel"] = 346;
$labels["myLabel"] = true;
$labels["myLabel"] = false;

$transaction = ["tags" => []];
$span = ["tags" => []];

$transaction["tags"]["intLabel"] = 1;
$transaction["tags"]["stringLabel"] = "abc";
$transaction["tags"]["boolLabel"] = true;

$span["tags"]["intLabel"] = 1;
$span["tags"]["stringLabel"] = "abc";
$span["tags"]["boolLabel"] = true;

$error = ["context" => ["tags" => ["intLabel" => 5, "stringLabel" => "test"]]];
$transaction["tags"]["boolLabel"] = true;

$transaction["tags"]["intLabel"] = 6;

$transaction["tags"]["foo1"] = "bar1";
$span["tags"]["foo2"] = "bar2";

$transaction["tags"] = ["foo" => "bar"];
$transaction["tags"]["item2"] = 123;
$transaction["tags"] = [];
$transaction["tags"]["intItem"] = 42;

$transaction["tags"]["fooT"] = 42;
$transaction["tags"]["barT"] = false;
$span["tags"]["fooS"] = 43;
$span["tags"]["barS"] = true;

$transaction["tags"]["foo"] = 42;
$transaction["tags"]["bar"] = false;
$transaction["tags"]["oldApi"] = "43";

json_encode($transaction, JSON_PRESERVE_ZERO_FRACTION);
json_encode($span, JSON_PRESERVE_ZERO_FRACTION);
json_encode($error, JSON_PRESERVE_ZERO_FRACTION);
?>
--EXPECT--
