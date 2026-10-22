--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/26)
--FILE--
<?php
$menu = [
    'File' => ['Open', 'Open with Plugin', null, 'Save All'],
    'Tools' => ['Batch Extractor', 'Batch Injector', 'Text Sequence Searcher', 'Hashes', 'Raw Image Viewer'],
    'Ciphers' => ['Decrypt', 'Encrypt'],
    'Compression' => ['Decompress', 'Compress'],
    'Settings' => ['Include Developer Builds' => (bool)random_int(0, 1)],
    'About' => 'About Kuriimu',
];

$layout = [
    'title' => 'Kuriimu2',
    'size' => [1116, 643],
    'padding' => 3,
    'allowDrop' => true,
    'tabs' => [],
    'status' => '',
    'progress' => 0,
];

$layout['tabs'][] = ['name' => 'Main', 'content' => $menu];
$layout['progress'] = count($menu) + count($layout['tabs']);
$layout['status'] = implode(' | ', array_keys($menu));

echo $layout['title'] . PHP_EOL;
echo $layout['size'][0] . 'x' . $layout['size'][1] . PHP_EOL;
echo $layout['status'] . PHP_EOL;
echo $layout['progress'] . PHP_EOL;
?>
--EXPECT--
