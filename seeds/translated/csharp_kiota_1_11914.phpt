--TEST--
Translated seed (inferredbugs-csharp: kiota/1)
--FILE--
<?php
$parts = [];
$parts[] = 'public class';
$parts[] = ' : ';
$parts[] = 'using Project.Graph;';
$parts[] = 'using System.Util;';
echo implode('', $parts);
?>
--EXPECT--
