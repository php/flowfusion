--TEST--
Translated seed (inferredbugs-csharp: Hangfire/3)
--FILE--
<?php
$pollInterval = -1;
if ($pollInterval < 0) {
    exit(0);
}
echo "ok\n";
?>
--EXPECT--
