--TEST--
Translated seed (inferredbugs-csharp: FbxWriter/1)
--FILE--
<?php
$sourceId = [0x58, 0xAB, 0xA9, 0xF0, 0x6C, 0xA2, 0xD8, 0x3F, 0x4D, 0x47, 0x49, 0xA3, 0xB4, 0xB2, 0xE7, 0x3D];
$key = [0xE2, 0x4F, 0x7B, 0x5F, 0xCD, 0xE4, 0xC8, 0x6D, 0xDB, 0xD8, 0xFB, 0xD7, 0x40, 0x58, 0xC6, 0x78];

$timestamp = ['Second' => 7, 'Month' => 11, 'Hour' => 9, 'Day' => 3, 'Millisecond' => 120, 'Year' => 2024, 'Minute' => 5];
$mangledtime = sprintf('%02d%02d%02d%02d%02d%04d%02d', $timestamp['Second'], $timestamp['Month'], $timestamp['Hour'], $timestamp['Day'], intdiv($timestamp['Millisecond'], 10), $timestamp['Year'], $timestamp['Minute']);
$mangledBytes = array_values(unpack('C*', $mangledtime));

$str = $sourceId;
$c = 64;
for ($i = 0; $i < 16; $i++) {
    $str[$i] = $str[$i] ^ ($c ^ $mangledBytes[$i]);
    $c = $str[$i];
}
$c = 64;
for ($i = 0; $i < 16; $i++) {
    $str[$i] = $str[$i] ^ ($c ^ $key[$i]);
    $c = $str[$i];
}
$c = 64;
for ($i = 0; $i < 16; $i++) {
    $str[$i] = $str[$i] ^ ($c ^ $mangledBytes[$i]);
    $c = $str[$i];
}

echo implode(' ', $str), PHP_EOL;
--EXPECT--
