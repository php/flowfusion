--TEST--
Translated seed (inferredbugs-java: aviator/5)
--FILE--
<?php

$options = [];
$options['map'] = [];
$options['trace'] = true;

function add_function($name, $function) {
    global $options;
    if (isset($options['map'][$name])) {
        echo "[WARN] The function '$name' already exists, replaced with new one.\n";
    }
    $options['map'][$name] = $function;
}

function execute($expression, $values = null) {
    global $options;
    if (empty($expression)) {
        throw new Exception("Blank expression");
    }
    if ($options['trace']) {
        echo "Executing: $expression\n";
    }
    if ($values) {
        $vars = array_keys($values);
        $valLen = count($values);
        if ($valLen != count($vars)) {
            throw new Exception("Expect " . count($vars) . " values, but has $valLen");
        }
        $env = array_combine($vars, array_values($values));
        return eval('return ' . strtr($expression, $env) . ';');
    }
    return eval($expression);
}

add_function('add', function($a, $b) { return $a + $b; });
add_function('sub', function($a, $b) { return $a - $b; });

$result = execute('add(1, 2)');
$result = execute('sub(5, 3)');
--EXPECT--
