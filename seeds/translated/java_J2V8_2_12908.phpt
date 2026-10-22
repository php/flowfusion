--TEST--
Translated seed (inferredbugs-java: J2V8/2)
--FILE--
<?php
$counter = 0;
$limit = 100;

for ($i = 0; $i <= $limit; $i++) {
    $counter++;
}

for ($i = 0; $i <= $limit; $i++) {
    $counter--;
}
?>
--EXPECT--
