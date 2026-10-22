--TEST--
Translated seed (inferredbugs-csharp: dnlib/6)
--FILE--
<?php
$streamHeaders = [
    ['Name' => '#x', 'Offset' => PHP_INT_MAX - 1, 'StreamSize' => 10],
    ['Name' => '#~', 'Offset' => 0, 'StreamSize' => 1],
];

$type = 'Unknown';
foreach ($streamHeaders as $sh) {
    if ($sh['Name'] === '#~') {
        $type = 'Compressed';
        break;
    }
    if ($sh['Name'] === '#-') {
        $type = 'ENC';
        break;
    }
}

foreach ($streamHeaders as $sh) {
    $sum = $sh['Offset'] + $sh['StreamSize'];
    if ($sum < $sh['Offset'] || $sum > 16) {
        continue;
    }
}

switch ($type) {
    case 'Compressed':
        $data = str_repeat("\0", 4);
        break;
    case 'ENC':
        $data = str_repeat("\1", 4);
        break;
    default:
        $data = '';
        break;
}

echo $data;
?>
--EXPECT--
