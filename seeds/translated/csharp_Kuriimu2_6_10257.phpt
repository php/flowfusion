--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/6)
--FILE--
<?php
$w = 1;
$h = 1;
$off = 0;
$encodings = [];
$swizzles = [];
$base = [
    ['Width:', &$w],
    ['Height:', &$h],
    ['Offset:', &$off],
    ['Encodings:', &$encodings],
    ['Swizzles:', &$swizzles],
];
$layout = [$base, ['Encoding Parameters', 'Swizzle Parameters']];
$state = [
    'title' => 'Raw Image Viewer',
    'size' => [500, 600],
    'padding' => 4,
    'widthLabel' => 'Width: 0',
    'heightLabel' => 'Height: 0',
    'statusColor' => 'DarkRed',
    'commands' => [
        'open' => ['menuText' => 'Open', 'enabled' => true],
        'close' => ['menuText' => 'Close', 'enabled' => false],
        'process' => ['enabled' => false],
    ],
    'content' => $layout,
];
echo "";
?>
--EXPECT--
