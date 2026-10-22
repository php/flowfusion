--TEST--
Translated seed (inferredbugs-java: aviator/4)
--FILE--
<?php

$options = [];
$functions = [];

function addFunction($name, $function) {
    global $functions;
    if (array_key_exists($name, $functions)) {
        echo "[WARN] The function '$name' already exists, but is replaced with a new one.\n";
    }
    $functions[$name] = $function;
}

function addOption($opt, $val) {
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

function execute($expression, $env = null) {
    // Simulating the execution of an expression
    if ($expression === "1 + 1") {
        return 2;
    }
    return null;
}

addFunction("add", function($a, $b) {
    return $a + $b;
});

addOption("trace", true);

$result = execute("1 + 1");
echo $result;

?>
--EXPECT--
