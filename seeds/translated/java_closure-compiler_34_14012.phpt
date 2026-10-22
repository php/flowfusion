--TEST--
Translated seed (inferredbugs-java: closure-compiler/34)
--FILE--
<?php

class CommandLineConfig {
    public $jsOutputFile = '';
    public $printTree = false;
    public $skipNormalOutputs = false;
    public $js = [];
    public $externs = [];
    public $module = [];
    public $createSourceMap = '';
}

class Compiler {
    public function compile($externs, $inputs, $options) {
        return ['success' => true, 'errors' => []];
    }
}

function createInputs($files) {
    return $files;
}

function writeOutput($out, $code) {
    file_put_contents($out, $code);
}

$config = new CommandLineConfig();
$config->jsOutputFile = 'output.js';
$config->js = ['input1.js', 'input2.js'];
$compiler = new Compiler();

$externs = createInputs($config->externs);
$inputs = createInputs($config->js);
$result = $compiler->compile($externs, $inputs, $config);

if ($result['success'] && !$config->skipNormalOutputs) {
    $code = "// Compiled JavaScript code\n";
    writeOutput($config->jsOutputFile, $code);
}
--EXPECT--
