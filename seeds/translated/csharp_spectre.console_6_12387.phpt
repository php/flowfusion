--TEST--
Translated seed (inferredbugs-csharp: spectre.console/6)
--FILE--
<?php
$decorations = [
    "\033[1m",
    "\033[2m",
    "\033[3m",
    "\033[4m",
    "\033[7m",
    "\033[8m",
    "\033[5m",
    "\033[6m",
    "\033[9m",
    "\033[1;4m",
    "\033[1;4;8m",
];

foreach ($decorations as $d) {
    echo $d, "Hello World", "\033[0m";
}
?>
--EXPECT--
