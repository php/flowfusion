--TEST--
Translated seed (inferredbugs-csharp: CSLightStudio/8)
--FILE--
<?php

class Item {
    public $path;
    public $test;

    public function __toString() {
        return $this->path;
    }
}

class Environment {
    // Dummy representation of the CLSE Environment
    public function createContent() {
        return new Content();
    }

    public function getTypeByKeyword($keyword) {
        return new Type();
    }
}

class Type {
    public $function;

    public function __construct() {
        $this->function = new TypeFunction();
    }
}

class TypeFunction {
    public function new($content, $params) {
        // Simulate instantiation of a type
        return new ScriptInstance();
    }

    public function memberCall($content, $instance, $methodName, $params) {
        return $instance->$methodName();
    }
}

class ScriptInstance {
    public function getI() {
        return (object) ['type' => 'integer', 'value' => 42];
    }
}

class Content {
    // Dummy representation of a content class
}

function logMessage($str) {
    echo $str . PHP_EOL;
}

function main() {
    $env = new Environment();
    
    $dirs = glob("script/*", GLOB_ONLYDIR);
    
    foreach ($dirs as $d) {
        try {
            $item = new Item();
            $item->path = $d;
            $item->test = file_get_contents($d . "/test.txt");

            // Simulate adding to a list
            // In actual implementation, you might store $item in an array or similar structure

        } catch (Exception $err) {
            // Log error if needed - currently skipped
        }
    }
    
    $content = $env->createContent();
    $type = $env->getTypeByKeyword("ScriptClass");
    $typeInst = $type->function->new($content, null);
    $value = $type->function->memberCall($content, $typeInst, "getI", null);
    
    logMessage("Run GetI= " . $value->type . "|" . $value->value);
}

main();
--EXPECT--
