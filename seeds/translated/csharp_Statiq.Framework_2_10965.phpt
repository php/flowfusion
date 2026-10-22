--TEST--
Translated seed (inferredbugs-csharp: Statiq.Framework/2)
--FILE--
<?php
$excludeKey = 'ExcludeFromEvaluation';
$key = 'MyKey';
$originalPrefix = 'prefix:';
$script = '1+1';
$value = $originalPrefix . $script;

$metadata = [
    $excludeKey => ['mykey', 'OtherKey']
];

$excluded = false;
if (array_key_exists($excludeKey, $metadata)) {
    $excludeObject = $metadata[$excludeKey];
    if (is_bool($excludeObject) && $excludeObject) {
        $excluded = true;
    } elseif (is_array($excludeObject)) {
        foreach ($excludeObject as $item) {
            if (is_string($item) && strcasecmp($item, $key) === 0) {
                $excluded = true;
                break;
            }
        }
    }
}

if ($excluded) {
    $result = $originalPrefix . $script;
} else {
    $result = eval('return ' . $script . ';');
}

echo $result;
?>
--EXPECT--
