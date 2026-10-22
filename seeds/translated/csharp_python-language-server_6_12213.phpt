--TEST--
Translated seed (inferredbugs-csharp: python-language-server/6)
--FILE--
<?php
$cases = [
    [null, 0],
    [null, 1],
    [1.0, 2],
    [1, 3],
    ["x", 4],
    [new stdClass(), 5],
];

foreach ($cases as [$expression, $position]) {
    $result = null;

    if (is_object($expression) && property_exists($expression, 'target') && $expression->target !== null && property_exists($expression, 'dotIndex') && property_exists($expression, 'startIndex') && $expression->dotIndex > $expression->startIndex && $position > $expression->dotIndex) {
        $result = 'member';
    } elseif (is_float($expression)) {
        $result = null;
    } elseif (is_string($expression) || $expression === null) {
        $result = null;
    } else {
        $result = 'top';
    }

    if ($result === null) {
        $result = 'empty';
    }

    echo $result, PHP_EOL;
}
?>
--EXPECT--
