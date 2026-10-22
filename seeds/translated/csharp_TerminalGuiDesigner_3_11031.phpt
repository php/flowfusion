--TEST--
Translated seed (inferredbugs-csharp: TerminalGuiDesigner/3)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_codegen_' . bin2hex(random_bytes(4));
@mkdir($dir);
$csFile = $dir . DIRECTORY_SEPARATOR . 'MyWindow1.cs';
$designerFile = $dir . DIRECTORY_SEPARATOR . 'MyWindow1.Designer.cs';

if (str_ends_with(basename($csFile), '.Designer.cs')) {
    throw new Exception('csFilePath should be a class file not the designer file');
}

$className = pathinfo($csFile, PATHINFO_FILENAME);
$ns = 'TerminalGuiDesigner';

$code = "<?php\nnamespace $ns;\nclass $className extends Window {\n    public function __construct() {\n        \$this->InitializeComponent();\n    }\n}\n";
file_put_contents($csFile, $code);

$designerClass = pathinfo(str_replace('.Designer.cs', '', basename($designerFile)), PATHINFO_FILENAME);
$designerCode = "<?php\nnamespace $ns;\nclass $designerClass {\n    public function InitializeComponent() {\n        \$this->myLabel = new Label('Test String');\n    }\n}\n";
file_put_contents($designerFile, $designerCode);

echo file_get_contents($csFile);
echo file_get_contents($designerFile);
?>
--EXPECT--
