--TEST--
Translated seed (inferredbugs-csharp: shriek-fx/1)
--FILE--
<?php
declare(strict_types=1);

$name = $argv[1] ?? 'admin';
usleep(1000000);
echo 'Fast->' . $name;
?>
--EXPECT--
