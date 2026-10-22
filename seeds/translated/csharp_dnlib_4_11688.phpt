--TEST--
Translated seed (inferredbugs-csharp: dnlib/4)
--FILE--
<?php
$mdSize = 0x50;
$streamHeaders = [
    ['Name' => '#~', 'Offset' => 0, 'StreamSize' => 0],
    ['Name' => '#-', 'Offset' => PHP_INT_MAX - 1, 'StreamSize' => 10],
    ['Name' => 'x', 'Offset' => 8, 'StreamSize' => 8],
];

$metaDataType = 'Unknown';
foreach ($streamHeaders as $sh) {
    if ($sh['Name'] === '#~') {
        $metaDataType = 'Compressed';
        break;
    }
    if ($sh['Name'] === '#-') {
        $metaDataType = 'ENC';
        break;
    }
}

foreach ($streamHeaders as $sh) {
    $sum = $sh['Offset'] + $sh['StreamSize'];
    if ($sum < $sh['Offset'] || $sum > $mdSize) {
        continue;
    }
}

if ($metaDataType === 'Unknown') {
    $metaDataType = 'Compressed';
}

switch ($metaDataType) {
    case 'Compressed':
        $data = str_repeat("\0", 16);
        break;
    case 'ENC':
        $data = str_repeat("\1", 16);
        break;
    default:
        $data = '';
        break;
}

unset($data);
echo "";
?>
--EXPECT--
