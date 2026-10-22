--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/11)
--FILE--
<?php
$items = [];
$items[] = ['text' => 'Clear', 'image' => 'delete'];
$items[] = ['text' => 'Cancel'];
$items[] = ['text' => 'Open', 'image' => 'open'];
$items[] = ['text' => 'Open with', 'image' => 'open_with'];
$items[] = ['text' => 'Save', 'shortcut' => 'Ctrl+S', 'image' => 'save'];
$items[] = ['text' => 'Save As', 'shortcut' => 'Ctrl+Shift+S', 'image' => 'save_as'];
$items[] = ['text' => 'Extract', 'image' => 'folder_export'];
$items[] = ['text' => 'Replace', 'image' => 'folder_import'];
$items[] = ['text' => 'Rename', 'image' => 'rename'];
$items[] = ['text' => 'Delete', 'image' => 'delete'];
$items[] = ['text' => 'Add', 'image' => 'add'];
$items[] = ['text' => 'Extract', 'image' => 'file_export'];
$items[] = ['text' => 'Replace', 'image' => 'file_import'];
$items[] = ['text' => 'Rename', 'image' => 'rename'];
$items[] = ['text' => 'Delete', 'image' => 'delete'];

$folders = [];
$files = [];

$folderContext = [$items[6], $items[7], $items[8], $items[10], $items[9]];
$fileContext = [$items[2], ['text' => 'Open with', 'command' => $items[3], 'image' => $items[3]['image']], null, $items[11], $items[12], $items[13], $items[14]];

$files[] = ['Name' => 'alpha', 'Size' => 10];
$files[] = ['Name' => 'beta', 'Size' => 2];
$files[] = ['Name' => 'gamma', 'Size' => 10];
$files[] = ['Name' => 'delta', 'Size' => 0];

usort($files, function ($a, $b) {
    return $a['Size'] <=> $b['Size'];
});

$search = "a";
$filtered = array_values(array_filter($files, function ($f) use ($search) {
    return stripos($f['Name'], $search) !== false;
}));

$archiveToolStrip = [$items[4], $items[5]];
$mainContent = [$folderContext, $fileContext, $filtered];
$content = [$archiveToolStrip, $mainContent];

echo count($content) . "\n";
?>
--EXPECT--
