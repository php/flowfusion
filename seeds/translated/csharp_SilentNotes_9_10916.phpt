--TEST--
Translated seed (inferredbugs-csharp: SilentNotes/9)
--FILE--
<?php
declare(strict_types=1);

$items = [];
for ($i = 0; $i < 10; $i++) {
    $items[] = str_repeat((string)$i, $i + 1);
}

$map = [];
foreach ($items as $k => $v) {
    $map[(string)$k] = $v;
}

$viewModel = null;
$viewModel = (object)[
    'items' => $items,
    'map' => $map,
    'script' => '',
];

$shortcuts = [
    ['key' => 'Escape', 'command' => 'GoBackCommand'],
];

$viewModel->script = json_encode([
    'model' => $viewModel,
    'shortcuts' => $shortcuts,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

$html = '<html><body>' . htmlspecialchars($viewModel->script, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</body></html>';

echo $html;
?>
--EXPECT--
