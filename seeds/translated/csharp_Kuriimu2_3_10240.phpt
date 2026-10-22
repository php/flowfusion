--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/3)
--FILE--
<?php
$files = new ArrayObject();
$files->append(['Name' => 'a', 'Size' => 1]);
$files->append(['Name' => 'b', 'Size' => 2]);

$folders = new ArrayObject();
$folders->append(['Name' => 'root', 'Children' => []]);

$search = '';
$search = (string)$search;
$search = strtoupper($search);
$search = strtolower($search);

$selected = [];
foreach ($files as $f) {
    if ($search === '' || strpos(strtolower($f['Name']), $search) !== false) {
        $selected[] = $f;
    }
}

$sorted = $files->getArrayCopy();
usort($sorted, function ($a, $b) {
    return $a['Name'] <=> $b['Name'];
});

$mapped = array_map(function ($f) {
    return $f['Name'] . ':' . $f['Size'];
}, $sorted);

$merged = array_merge(iterator_to_array($folders), $mapped);
foreach ($merged as $v) {
    if (is_array($v)) {
        $tmp = $v['Name'] ?? '';
    } else {
        $tmp = (string)$v;
    }
}

echo "";
?>
--EXPECT--
