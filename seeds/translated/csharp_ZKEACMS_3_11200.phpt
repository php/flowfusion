--TEST--
Translated seed (inferredbugs-csharp: ZKEACMS/3)
--FILE--
<?php
$layoutId = trim((string)($argv[1] ?? ''));
$pageId = trim((string)($argv[2] ?? ''));
$zoneId = trim((string)($argv[3] ?? ''));

$layouts = [
    'L1' => ['ID' => 'L1', 'Html' => '<div>A</div>', 'IsTemplate' => false, 'Page' => null],
    'L2' => ['ID' => 'L2', 'Html' => '<div>B</div>', 'IsTemplate' => true, 'Page' => null],
];
$pages = [
    'P1' => ['ID' => 'P1', 'LayoutId' => 'L1'],
];

$layout = null;
if ($layoutId !== '') {
    $layout = $layouts[$layoutId] ?? null;
}
if ($layout === null && $pageId !== '') {
    $page = $pages[$pageId] ?? null;
    if ($page !== null) {
        $layout = $layouts[$page['LayoutId']] ?? null;
    }
}
if ($pageId !== '' && $layout !== null) {
    $layout['Page'] = ['ID' => $pageId];
}

$htmlParts = ['<section>', '<div data-zone="' . htmlspecialchars($zoneId, ENT_QUOTES, 'UTF-8') . '">x</div>', '</section>'];
$zones = [$zoneId => ['ID' => $zoneId, 'Name' => 'Zone']];
$layout['Html'] = implode('', $htmlParts);
$layout['Zones'] = $zones;

if ($layout !== null && isset($layout['Page'])) {
    $out = $layout['Page']['ID'];
} elseif ($layout !== null) {
    $out = $layout['ID'];
} else {
    $out = 'default';
}

echo $layout['Html'] . "\n" . $out . "\n";
--EXPECT--
