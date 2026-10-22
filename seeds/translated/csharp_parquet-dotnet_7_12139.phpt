--TEST--
Translated seed (inferredbugs-csharp: parquet-dotnet/7)
--FILE--
<?php
$schema = [
    ['name' => 'root', 'converted_type' => 'MAP', 'num_children' => 1, 'repetition_type' => 'OPTIONAL'],
    ['name' => 'key_value', 'num_children' => 2, 'repetition_type' => 'REPEATED'],
    ['name' => 'k', 'repetition_type' => 'REPEATED'],
    ['name' => 'v', 'repetition_type' => 'REPEATED'],
];

$index = 0;
$root = $schema[$index];
$container = $schema[++$index];

if ($container['num_children'] !== 2) {
    throw new OutOfRangeException('dictionary container must have exactly 2 children but ' . $container['num_children'] . ' found');
}

$path = $root['name'] . '/' . $container['name'];
$index += 1;
$ownedChildCount = 2;

if (($root['converted_type'] ?? null) === 'MAP' || ($root['converted_type'] ?? null) === 'MAP_KEY_VALUE') {
    $match = true;
} else {
    $match = false;
}

if ($schema[2]['repetition_type'] === 'REPEATED') {
    $schema[2]['repetition_type'] = 'OPTIONAL';
}
if ($schema[3]['repetition_type'] === 'REPEATED') {
    $schema[3]['repetition_type'] = 'OPTIONAL';
}

echo $path, "\n";
echo $ownedChildCount, "\n";
echo ($match ? '1' : '0'), "\n";
?>
--EXPECT--
