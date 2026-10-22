--TEST--
Translated seed (inferredbugs-csharp: SharpBgfx/1)
--FILE--
<?php
$form = ['text' => 'Draw Stress Test', 'clientSize' => [920, 760]];
$renderers = function_exists('get_loaded_extensions') ? get_loaded_extensions() : [];
$current = PHP_SAPI;
$name = phpversion();
$caps = [
    'os' => PHP_OS,
    'php' => PHP_VERSION,
    'sapi' => PHP_SAPI,
    'extensions' => $renderers,
];
echo "";
?>
--EXPECT--
