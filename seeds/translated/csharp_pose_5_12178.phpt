--TEST--
Translated seed (inferredbugs-csharp: pose/5)
--FILE--
<?php
$original = null;
$replacement = null;
$instance = null;

$expr = function () use (&$instance) {
    $instance = new stdClass();
    $instance->value = 1;
    return $instance->value;
};

$original = $expr;
$replacement = function () {
    return 2;
};

$instance = null;
$instance = (function () use (&$instance) {
    $instance = new stdClass();
    $instance->value = 1;
    return $instance->value;
})();

$replacement = function () use ($instance) {
    return $instance->value + 1;
};

$original();
$replacement();
?>
--EXPECT--
