--TEST--
Translated seed (inferredbugs-csharp: Rhisis/4)
--FILE--
<?php
$path = $argv[1] ?? null;
if ($path === null) {
    $path = tempnam(sys_get_temp_dir(), 'wld_');
    file_put_contents($path, "  // comment\nSIZE 1,234 5\nindoor 1\nfly 0\nmpu 42\nrevival 7 \"key\"\n");
}

$sizeX = 0;
$sizeZ = 0;
$isIndoor = false;
$canFly = false;
$mpu = 0;
$revivalMapId = 0;
$revivalKey = '';

$lines = file($path, FILE_IGNORE_NEW_LINES);
foreach ($lines as $line) {
    $line = strtolower(trim($line));
    if ($line === '' || str_starts_with($line, '//')) {
        continue;
    }
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

echo $sizeX . ' ' . $sizeZ . ' ' . $mpu . ' ' . ($isIndoor ? '1' : '0') . ' ' . ($canFly ? '1' : '0') . ' ' . $revivalMapId . ' ' . $revivalKey . PHP_EOL;
?>
--EXPECT--
