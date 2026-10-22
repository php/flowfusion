--TEST--
Translated seed (inferredbugs-csharp: TerminalGuiDesigner/4)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_port_' . bin2hex(random_bytes(4));
@mkdir($dir);

$base = 'MyWindow1';
$csFile = $dir . DIRECTORY_SEPARATOR . $base . '.cs';
$designerFile = $dir . DIRECTORY_SEPARATOR . $base . '.Designer.cs';

if (str_ends_with($csFile, '.Designer.cs')) {
    throw new Exception('csFilePath should be a class file not the designer file');
}

$namespaceName = 'TerminalGuiDesigner';
$indent = '    ';

$code = "namespace $namespaceName {\n" .
        $indent . "using Terminal.Gui;\n\n" .
        $indent . "public partial class $base : Window {\n" .
        $indent . $indent . "public $base() {\n" .
        $indent . $indent . $indent . "InitializeComponent();\n" .
        $indent . $indent . "}\n" .
        $indent . "}\n" .
        "}\n";
file_put_contents($csFile, $code);

$w = ['type' => 'Window', 'children' => [['type' => 'Label', 'text' => 'Hello World']]];

$designer = "namespace $namespaceName {\n" .
            $indent . "using System;\n" .
            $indent . "using Terminal.Gui;\n\n" .
            $indent . "public partial class $base {\n" .
            $indent . $indent . "void InitializeComponent() {\n" .
            $indent . $indent . $indent . "var myLabel = new Label(\"Test String\");\n" .
            $indent . $indent . "}\n" .
            $indent . "}\n" .
            "}\n";
file_put_contents($designerFile, $designer);

echo "ok\n";
--EXPECT--
