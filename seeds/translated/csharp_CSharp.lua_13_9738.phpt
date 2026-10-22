--TEST--
Translated seed (inferredbugs-csharp: CSharp.lua/13)
--FILE--
<?php

$kStringConstInlineCount = 27;

function runBugTriggeringTest() {
    $a = "0"; 
    $b = 1; 
    $result = $a + $b; 
    var_dump($result); 
}

runBugTriggeringTest();
--EXPECT--
