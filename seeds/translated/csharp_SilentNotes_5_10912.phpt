--TEST--
Translated seed (inferredbugs-csharp: SilentNotes/5)
--FILE--
<?php
$shortcuts = [
    ['key' => 'Escape', 'command' => 'GoBackCommand'],
];
$viewModel = new stdClass();
$viewModel->shortcuts = $shortcuts;
$viewModel->vueDataBindingScript = json_encode($viewModel->shortcuts, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
$html = '<html><body>' . htmlspecialchars($viewModel->vueDataBindingScript, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</body></html>';
echo $html;
?>
--EXPECT--
