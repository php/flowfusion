--TEST--
Translated seed (inferredbugs-java: closure-compiler/6)
--FILE--
<?php

$x = 0;

if (false) {
    $x = 1;
} else {
    $x = 2;
}

for ($i = 0; $i < 0; $i++) {
    $x++;
}

while (false) {
    $x++;
}

do {
    $x++;
} while (false);

$x = (true) ? 1 : 0;

echo $x;
--EXPECT--
