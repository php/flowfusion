--TEST--
Translated seed (inferredbugs-csharp: Rhisis/3)
--FILE--
<?php
$input = "  // comment
SIZE 1,234 567
indoor 1
fly 0
mpu 42
revival 7 \"spawn_key\"
";

$sizeX = 0;
$sizeZ = 0;
$isIndoor = false;
$canFly = false;
$mpu = 0;
$revivalMapId = 0;
$revivalKey = '';

foreach (preg_split("/\R/", $input) as $line) {
    $line = strtolower(trim($line));
    if ($line === '' || str_starts_with($line, '//')) continue;
    $parts = preg_split('/[ \t]+/', $line, -1, PREG_SPLIT_NO_EMPTY);
    switch ($parts[0]) {
        case 'size':
            $sizeX = (int)str_replace(',', '', $parts[1]);
            $sizeZ = (int)$parts[2];
            break;
        case 'indoor':
            $isIndoor = $parts[1] === '1';
            break;
        case 'fly':
            $canFly = $parts[1] === '1';
            break;
        case 'mpu':
            $mpu = (int)$parts[1];
            break;
        case 'revival':
            $revivalMapId = (int)$parts[1];
            $revivalKey = trim($parts[2], '"');
            break;
    }
}

$worldInformations = [
    'sizeX' => $sizeX,
    'sizeZ' => $sizeZ,
    'mpu' => $mpu,
    'isIndoor' => $isIndoor,
    'canFly' => $canFly,
    'revivalMapId' => $revivalMapId,
    'revivalKey' => $revivalKey,
];

echo '';
?>
--EXPECT--
