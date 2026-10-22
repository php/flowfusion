--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/88)
--FILE--
<?php
$seg = ["tags" => []];
$seg["tags"]["myLabel"] = "StrValue";
$seg["tags"]["intLabel"] = 1;
$seg["tags"]["stringLabel"] = "abc";
$seg["tags"]["boolLabel"] = true;
$seg["tags"]["foo1"] = "bar1";
$seg["tags"]["foo2"] = "bar2";
$seg["tags"]["foo"] = "bar";
$seg["tags"]["item2"] = 123;
$seg["tags"]["intItem"] = 42;
$seg["tags"]["oldApi"] = "43";
unset($seg["tags"]["foo"]);
$seg["tags"]["intLabel"] = 6;
$seg["tags"]["myLabel2"] = 234.0;
$seg["tags"]["myLabel3"] = 346;
$seg["tags"]["myLabel4"] = false;
$seg["tags"]["myLabel5"] = 457;
echo json_encode($seg, JSON_PRESERVE_ZERO_FRACTION), PHP_EOL;
?>
--EXPECT--
