--TEST--
Translated seed (inferredbugs-csharp: ultraviolet/10)
--FILE--
<?php
$eventName = '';
if ($eventName === '') {
    $eventName = 'click';
}
$parts = explode(':', $eventName, 2);
$owner = $parts[0];
$name = $parts[1] ?? $parts[0];
$registry = [
    'Button' => ['click' => true, 'hover' => true],
    'TextBox' => ['input' => true],
];
$targetType = 'Button';
if (!isset($registry[$targetType][$name])) {
    $name = 'click';
}
$subscribers = [];
$key = $targetType . ':' . $name;
$subscribers[$key] = ($subscribers[$key] ?? 0) + 1;
if (isset($subscribers[$key])) {
    $subscribers[$key]--;
    if ($subscribers[$key] === 0) {
        unset($subscribers[$key]);
    }
}
echo $owner . ':' . $name . PHP_EOL;
--EXPECT--
