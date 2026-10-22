--TEST--
Translated seed (inferredbugs-csharp: TerminalGuiDesigner/1)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_port_' . bin2hex(random_bytes(4));
@mkdir($dir);

$csFile = $dir . DIRECTORY_SEPARATOR . 'MyWindow1.cs';
$designerFile = $dir . DIRECTORY_SEPARATOR . 'MyWindow1.Designer.cs';

if (str_ends_with(basename($csFile), '.Designer.cs')) {
    throw new Exception('csFilePath should be a class file not the designer file');
}

$className = pathinfo($csFile, PATHINFO_FILENAME);

$code = "<?php\nclass $className {\n    public function __construct() {\n        \$this->initializeComponent();\n    }\n}\n";
file_put_contents($csFile, $code);

$view = ['type' => 'Window', 'children' => [['type' => 'Label', 'text' => 'Hello World']]];

$designerClass = pathinfo(str_replace('.Designer.cs', '', basename($designerFile)), PATHINFO_FILENAME);
$designer = "<?php\nclass $designerClass {\n    private \$myLabel;\n    private function initializeComponent() {\n        \$this->myLabel = new stdClass();\n        \$this->myLabel->text = 'Hello World';\n        \$this->add(\$this->myLabel);\n    }\n}\n";
file_put_contents($designerFile, $designer);

echo $view['children'][0]['text'];
?>
--EXPECT--
