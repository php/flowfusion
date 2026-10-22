--TEST--
Translated seed (inferredbugs-java: closure-compiler/19)
--FILE--
<?php
$maxInt = PHP_INT_MAX;
$overflowedValue = $maxInt + 1;
echo $overflowedValue . "\n";

$array = [];
for ($i = 0; $i < 10; $i++) {
    $array[] = $i;
}

for ($i = 0; $i < 15; $i++) {
    $array[$i] = $i;
}
echo count($array) . "\n";

$number = 1.5;
$integerValue = (int)$number;
echo $integerValue . "\n";

$stringValue = "100";
$sum = $stringValue + 50;
echo $sum . "\n";

$largeNumber = 1e20;
$stringFromNumber = (string)$largeNumber;
echo $stringFromNumber . "\n";

$nullValue = null;
$sumWithNull = $nullValue + 5;
echo $sumWithNull . "\n";
?>
--EXPECT--
