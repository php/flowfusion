--TEST--
Translated seed (inferredbugs-csharp: puppeteer-sharp/1)
--FILE--
<?php
$objs = [];
$objs["0"] = "a";
$objs[0] = "b";
$objs["00"] = "c";
$objs[0.0] = "d";
$objs[true] = "e";
$objs[false] = "f";
$objs[null] = "g";

$s = [];
$s["x"] = ["id" => 1, "closed" => false];
$s["y"] = ["id" => 2, "closed" => false];

foreach (array_keys($s) as $k) {
    if ($k === "x") {
        unset($s[$k]);
    }
}

$pending = [];
for ($i = 0; $i < 3; $i++) {
    $pending[(string)$i] = ["method" => "m$i", "result" => null];
}

$json = '{"id":1,"result":{"n":9223372036854775807},"method":null,"params":{"sessionId":"x"}}';
$obj = json_decode($json, true);
if (isset($obj["id"])) {
    unset($pending[(string)$obj["id"]]);
}

$bad = json_decode("{", true);
if ($bad === null && json_last_error() !== JSON_ERROR_NONE) {
    $msg = "bad";
}

foreach ($pending as $k => $v) {
    $pending[$k]["result"] = $v["method"];
}

echo count($objs) . "\n";
echo count($s) . "\n";
echo count($pending) . "\n";
echo $msg . "\n";
?>
--EXPECT--
