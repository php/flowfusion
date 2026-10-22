--TEST--
Translated seed (inferredbugs-csharp: MefContrib/1)
--FILE--
<?php
$genericTypes = [];
$manufacturedParts = [];
$catalog = [];

$definitions = [
    ['contract' => 'List<int>', 'type' => 'List'],
    ['contract' => 'List<string>', 'type' => 'List'],
    ['contract' => 'Map<int,string>', 'type' => 'Map'],
    ['contract' => 'List<int>', 'type' => 'List'],
];

foreach ($definitions as $def) {
    $importType = $def['type'];
    if (!isset($catalog[$importType])) {
        if (!isset($genericTypes[$importType])) {
            $genericTypes[$importType] = $importType . '<closed>';
        }
        $manufacturedParts[] = $importType;
        $catalog[$importType] = true;
    }
    if (in_array($importType, $manufacturedParts, true)) {
        $catalog[$importType] = true;
    }
}

foreach ($genericTypes as $k => $v) {
    echo $k . '=' . $v . PHP_EOL;
}
foreach ($manufacturedParts as $p) {
    echo $p . PHP_EOL;
}
--EXPECT--
