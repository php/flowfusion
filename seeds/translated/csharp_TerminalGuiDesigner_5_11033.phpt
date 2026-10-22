--TEST--
Translated seed (inferredbugs-csharp: TerminalGuiDesigner/5)
--FILE--
<?php
$csFilePath = $argv[1] ?? 'MyWindow1.cs';
$namespaceName = $argv[2] ?? 'TerminalGuiDesigner';

if (str_ends_with($csFilePath, '.Designer.cs')) {
    throw new Exception('csFilePath should be a class file not the designer file');
}

$className = pathinfo($csFilePath, PATHINFO_FILENAME);
$indent = "    ";

$cs = "namespace $namespaceName {\n{$indent}public partial class $className : Window {\n{$indent}{$indent}public $className() {\n{$indent}{$indent}{$indent}InitializeComponent();\n{$indent}{$indent}}\n{$indent}}\n}\n";
file_put_contents($csFilePath, $cs);

$w = (object)['Subviews' => []];
$lbl = (object)['Data' => 'label1', 'Subviews' => []];
$w->Subviews[] = $lbl;

$walk = function ($view) use (&$walk) {
    foreach ($view->Subviews as $sub) {
        if (is_object($sub->Data ?? null) && ($sub->Data->type ?? null) === 'Design') {
        }
        $walk($sub);
    }
};
$walk($w);

$designer = "namespace $namespaceName {\n{$indent}using System;\n{$indent}using Terminal.Gui;\n{$indent}public partial class $className {\n{$indent}{$indent}private void InitializeComponent() {\n{$indent}{$indent}}\n{$indent}}\n}\n";
file_put_contents(preg_replace('/\.cs$/', '.Designer.cs', $csFilePath), $designer);
?>
--EXPECT--
