--TEST--
Translated seed (inferredbugs-java: J2V8/9)
--FILE--
<?php
$array = [1, 2, 3, 4, 5];
$result = [];
foreach ($array as $value) {
    $result[] = $value * 2;
}
$nestedArray = [['a', 'b'], ['c', 'd']];
$result2 = [];
foreach ($nestedArray as $subArray) {
    $temp = [];
    foreach ($subArray as $value) {
        $temp[] = strtoupper($value);
    }
    $result2[] = $temp;
}
print_r($result);
print_r($result2);
?>
--EXPECT--
