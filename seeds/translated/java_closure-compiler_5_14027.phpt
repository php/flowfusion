--TEST--
Translated seed (inferredbugs-java: closure-compiler/5)
--FILE--
<?php

$cond = false;

if ($cond) {
    echo "This won't execute.\n";
} else {
    echo "This will execute.\n";
}

if (true) {
    echo "This will always execute.\n";
} else {
    echo "This won't execute.\n";
}

for ($i = 0; $i < 0; $i++) {
    echo "This won't execute.\n";
}

while (false) {
    echo "This won't execute either.\n";
}

do {
    echo "This won't execute either.\n";
} while (false);

$unusedVar = 0;
$unusedVar2 = 1;
$result = $unusedVar2 + 1; // This has an effect

echo "End of script.\n";
--EXPECT--
