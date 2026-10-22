--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/10)
--FILE--
<?php
$items = [];
$items[] = ['image' => null, 'text' => ''];
$items[] = ['image' => str_repeat("\0", 3), 'text' => "0"];
$items[] = ['image' => "\xFF\xD8\xFF", 'text' => "jpeg"];
$items[] = ['image' => random_bytes(1), 'text' => "pal"];
$items[] = ['image' => '', 'text' => str_repeat("A", 1024)];

$width = 0;
$height = -1;
$formats = ['indexed', 'rgb', 'rgba', 'grayscale'];
$palettes = [[], [0, 0, 0], [255, 255, 255]];
$selectedFormat = $formats[array_key_last($formats)];
$selectedPalette = $palettes[0];

$toolstrip = [
    'save',
    'saveas',
    null,
    'export',
    'import',
];

$layout = [
    'image' => ['stretch' => true, 'size' => [200, -1]],
    'labels' => ['width' => $width, 'height' => $height],
    'format' => $selectedFormat,
    'palette' => $selectedPalette,
    'items' => $items,
    'toolstrip' => $toolstrip,
];

foreach ($layout['items'] as $i => $item) {
    $layout['items'][$i]['text'] = (string)$item['text'];
    $layout['items'][$i]['image_len'] = strlen($item['image']);
}

$layout['labels']['width'] = (int)$layout['labels']['width'];
$layout['labels']['height'] = (int)$layout['labels']['height'];

echo "";
?>
--EXPECT--
