--TEST--
Translated seed (inferredbugs-csharp: SilentNotes/4)
--FILE--
<?php
$viewModel = null;
$shortcuts = [['key' => 'Escape', 'command' => 'GoBackCommand']];
$bindings = ['model' => &$viewModel, 'shortcuts' => $shortcuts];
$viewModel = ['VueDataBindingScript' => ''];
$viewModel['VueDataBindingScript'] = json_encode($bindings, JSON_UNESCAPED_SLASHES);
$html = '<html><body>' . htmlspecialchars($viewModel['VueDataBindingScript'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</body></html>';
echo $html;
?>
--EXPECT--
