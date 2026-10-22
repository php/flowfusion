--TEST--
Translated seed (inferredbugs-java: SimpleFlatMapper/7)
--FILE--
<?php

class ObjectWithIncompatibleConstructor {
    private $arg1;
    private $arg2;
    private $arg3;

    public function __construct($arg1, $arg2) {
        $this->arg1 = $arg1;
        $this->arg2 = $arg2;
        $this->arg3 = null;
    }
    public function __constructWithArg3($arg1, $arg3) {
        $this->arg1 = $arg1;
        $this->arg2 = null;
        $this->arg3 = $arg3;
    }
}

function findProperty($propertyName) {
    if (in_array($propertyName, ["1_arg1", "1_arg3", "2_arg1", "2_arg2"])) {
        return true;
    }
    return null;
}

$propertyFinder = 'new PropertyFinder'; // This is a placeholder for actual class instantiation and property finding logic.

$propEltId1 = findProperty("1_arg1");
$propEltId2 = findProperty("1_arg3");
$propEltId3 = findProperty("2_arg1");
$propEltId4 = findProperty("2_arg2");
$propEltId5 = findProperty("1_arg2"); // Check for non-existent property
--EXPECT--
