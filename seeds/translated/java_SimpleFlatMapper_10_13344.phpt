--TEST--
Translated seed (inferredbugs-java: SimpleFlatMapper/10)
--FILE--
<?php

class ObjectWithIncompatibleConstructor {
    private $arg1;
    private $arg2;
    private $arg3;

    public function __construct($arg1, $arg2 = null, $arg3 = null) {
        $this->arg1 = $arg1;
        if (is_int($arg2)) {
            $this->arg2 = null;
            $this->arg3 = $arg2;
        } elseif (is_numeric($arg2) && $arg2 !== null) {
            $this->arg2 = $arg2;
            $this->arg3 = null;
        } else {
            $this->arg2 = $arg2;
            $this->arg3 = null;
        }
    }
}

function findProperty($property) {
    $properties = ['1_arg1', '1_arg3', '2_arg1', '2_arg2'];
    return in_array($property, $properties) ? $property : null;
}

$propEltId = findProperty("1_arg1");
if ($propEltId !== null) {}
$propEltId = findProperty("1_arg3");
if ($propEltId !== null) {}
$propEltId = findProperty("2_arg1");
if ($propEltId !== null) {}
$propEltId = findProperty("2_arg2");
if ($propEltId !== null) {}
$propEltId = findProperty("1_arg2");
if ($propEltId === null) {}
--EXPECT--
