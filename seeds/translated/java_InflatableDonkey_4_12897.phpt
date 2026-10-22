--TEST--
Translated seed (inferredbugs-java: InflatableDonkey/4)
--FILE--
<?php

$input = 1;
$output = 1;

for ($i = 1; $i <= 20; $i++) {
    $output *= $input;
    if ($output < $input) {
        break;
    }
    $input++;
}
--EXPECT--
