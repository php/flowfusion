--TEST--
Translated seed (inferredbugs-csharp: ILRuntime/5)
--FILE--
<?php
$path = $argv[1] ?? __FILE__;
$fp = fopen($path, 'rb');
if ($fp === false) {
    exit(0);
}
$dir = dirname($path);
$name = pathinfo($path, PATHINFO_FILENAME);
$pdb = $dir . DIRECTORY_SEPARATOR . $name . '.pdb';
if (!is_file($pdb)) {
    $name = basename($path);
    $pdb = $dir . DIRECTORY_SEPARATOR . $name . '.mdb';
}
$fp2 = fopen($pdb, 'rb');
if ($fp2 !== false) {
    fclose($fp2);
}
$types = [
    ['kind' => 'ILType', 'fullName' => 'A', 'methods' => [
        ['name' => 'M0', 'parameterCount' => 0, 'isStatic' => true, 'public' => true],
        ['name' => 'M1', 'parameterCount' => 1, 'isStatic' => true, 'public' => true],
        ['name' => 'M2', 'parameterCount' => 0, 'isStatic' => false, 'public' => true],
    ]],
    ['kind' => 'Other', 'fullName' => 'B', 'methods' => []],
];
$testUnits = [];
foreach ($types as $type) {
    if (($type['kind'] ?? null) !== 'ILType') {
        continue;
    }
    foreach ($type['methods'] as $methodInfo) {
        if (($methodInfo['parameterCount'] ?? 1) === 0 && !empty($methodInfo['isStatic']) && !empty($methodInfo['public'])) {
            $testUnits[] = [$type['fullName'], $methodInfo['name']];
        }
    }
}
if (is_resource($fp)) {
    fclose($fp);
}
$testUnits = [];
?>
--EXPECT--
