--TEST--
Translated seed (inferredbugs-csharp: opencensus-csharp/1)
--FILE--
<?php
$state = 'ENABLED';
$read = false;

$value = $state;
$read = true;

if (!$read) {
    $newState = 'DISABLED';
    if ($newState !== $state) {
        $state = $newState;
    }
}
?>
--EXPECT--
