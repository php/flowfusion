--TEST--
Translated seed (inferredbugs-csharp: parquet-dotnet/5)
--FILE--
<?php
$schema = [
    ['Name' => 'root'],
    ['Name' => 'key_value', 'Num_children' => 2],
];

$index = 0;
$tseRoot = $schema[$index];
$tseContainer = $schema[++$index];

if (($tseContainer['Num_children'] ?? null) !== 2) {
    throw new RuntimeException('dictionary container must have exactly 2 children');
}

$map = [
    'Name' => $tseRoot['Name'],
    'Path' => $tseRoot['Name'] . '/' . $tseContainer['Name'],
];

$index += 1;
$ownedChildCount = 2;

echo $map['Path'], "\n";
echo $ownedChildCount, "\n";
?>
--EXPECT--
