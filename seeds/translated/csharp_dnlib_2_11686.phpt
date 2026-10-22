--TEST--
Translated seed (inferredbugs-csharp: dnlib/2)
--FILE--
<?php
$mdSize = 0x48;
$streams = [
    ['Offset' => 0, 'Size' => 8, 'Name' => '#Strings'],
    ['Offset' => 8, 'Size' => 16, 'Name' => '#US'],
    ['Offset' => 24, 'Size' => 8, 'Name' => '#Blob'],
    ['Offset' => 32, 'Size' => 16, 'Name' => '#GUID'],
    ['Offset' => 48, 'Size' => 8, 'Name' => '#~'],
    ['Offset' => 56, 'Size' => 8, 'Name' => '#Schema'],
];

foreach ($streams as $sh) {
    if ($sh['Offset'] + $sh['Size'] < $sh['Offset'] || $sh['Offset'] > $mdSize || $sh['Offset'] + $sh['Size'] > $mdSize) {
        throw new Exception('Invalid stream header');
    }
    $data = str_repeat("\0", $sh['Size']);
    switch ($sh['Name']) {
        case '#Strings':
        case '#US':
        case '#Blob':
        case '#GUID':
        case '#~':
        case '#-':
        case '#Schema':
        default:
            $tmp = $data;
            break;
    }
}
?>
--EXPECT--
