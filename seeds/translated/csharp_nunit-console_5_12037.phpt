--TEST--
Translated seed (inferredbugs-csharp: nunit-console/5)
--FILE--
<?php
$parentPid = getmypid();
$setupPid = getmypid();

if (getmypid() !== $parentPid) {
    exit(1);
}

if (getmypid() !== $setupPid) {
    exit(1);
}

$start = microtime(true);
while (microtime(true) - $start < 0.05) {
}

echo "ok\n";
?>
--EXPECT--
