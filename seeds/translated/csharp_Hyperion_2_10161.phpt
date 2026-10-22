--TEST--
Translated seed (inferredbugs-csharp: Hyperion/2)
--FILE--
<?php
$strings = ["abc", "def", null, "ghi", "jkl", "lmmo"];
$s = serialize($strings);
$u = unserialize($s);

$l = array_values($strings);
$s = serialize($l);
$u = unserialize($s);

for ($i = 0; $i < 100000; $i++) {
    $poco = ["Age" => 123, "Name" => "Hej"];
    $s = serialize($poco);
    $u = unserialize($s);
}

for ($i = 0; $i < 100000; $i++) {
    $poco = ["Age" => 123, "Name" => "Hej"];
    $loco = ["YesNo" => true, "Poco" => $poco];
    $s = serialize($loco);
    $u = unserialize($s);
}
?>
--EXPECT--
