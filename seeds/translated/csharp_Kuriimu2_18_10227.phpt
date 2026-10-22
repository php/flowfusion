--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/18)
--FILE--
<?php
$files = new ArrayObject([], ArrayObject::ARRAY_AS_PROPS);
$files->append((object)['Name' => 'a', 'Size' => 1]);
$files->append((object)['Name' => 'b', 'Size' => 2]);

$folder = new ArrayObject();
$folder[] = ['name' => 'root', 'children' => $files];

$menu = [
    'open' => 'Open',
    'openWith' => 'Open with',
    'save' => 'Save',
    'saveAs' => 'Save As',
    'extract' => 'Extract',
    'replace' => 'Replace',
    'rename' => 'Rename',
    'delete' => 'Delete',
    'add' => 'Add',
    'clear' => 'Clear',
    'cancel' => 'Cancel',
];

$search = '';
$search = strtolower($search);
$filtered = new ArrayObject();
foreach ($files as $f) {
    if ($search === '' || strpos(strtolower($f->Name), $search) !== false) {
        $filtered->append($f);
    }
}

$sorted = iterator_to_array($filtered);
usort($sorted, function ($a, $b) {
    return strcmp($a->Name, $b->Name);
});

$archive = [
    'folders' => $folder,
    'files' => $sorted,
    'menu' => $menu,
];

echo count($archive['files']) . PHP_EOL;
echo $archive['files'][0]->Name . ':' . $archive['files'][0]->Size . PHP_EOL;
echo $archive['menu']['save'] . PHP_EOL;
?>
--EXPECT--
