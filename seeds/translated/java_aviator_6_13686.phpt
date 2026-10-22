--TEST--
Translated seed (inferredbugs-java: aviator/6)
--FILE--
<?php
$funcMap = [];
$options = [];

function addFunction($name, $function) {
    global $funcMap;
    if (isset($funcMap[$name])) {
        echo "[WARN] The function '{$name}' already exists, but is replaced with a new one.\n";
    }
    $funcMap[$name] = $function;
}

function setOption($opt, $val) {
    global $options;
    if ($opt === null || $val === null) {
        throw new InvalidArgumentException("Option and value should not be null.");
    }
    $options[$opt] = $val;
}

function getOption($opt) {
    global $options;
    return $options[$opt] ?? null;
}

function compile($expression) {
    if (trim($expression) === '') {
        throw new Exception("Blank expression");
    }
    return eval("return $expression;");
}

function execute($expression) {
    $compiledExpression = compile($expression);
    return $compiledExpression;
}

function loadLib() {
    addFunction('add', function($a, $b) { return $a + $b; });
    addFunction('sub', function($a, $b) { return $a - $b; });
    addFunction('mul', function($a, $b) { return $a * $b; });
    addFunction('div', function($a, $b) { return $a / $b; });
    addFunction('mod', function($a, $b) { return $a % $b; });
}

loadLib();
$result = execute('add(2147483647, 1)');
echo $result;
?>
--EXPECT--
