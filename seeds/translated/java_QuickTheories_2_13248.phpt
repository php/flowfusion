--TEST--
Translated seed (inferredbugs-java: QuickTheories/2)
--FILE--
<?php

class MyClassLoader {
    public function createClass($bytes) {
        return eval('return "' . addslashes($bytes) . '";');
    }
}

function classAsBytes($className) {
    $fileName = str_replace('.', '/', $className) . '.class';
    $content = file_get_contents($fileName);
    return $content ? array_map('ord', str_split($content)) : [];
}

$classLoader = new MyClassLoader();
$classBytes = classAsBytes('Example.ClassName');
$classLoader->createClass(implode(array_map('chr', $classBytes)));
--EXPECT--
