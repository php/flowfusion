--TEST--
Translated seed (inferredbugs-csharp: HarmonyX/3)
--FILE--
<?php
class Class15Patch {
    public static function TargetMethod() {
        return null;
    }
}

class Class16Patch {
    public static function TargetMethod() {
        return 123;
    }
}

class Class17Patch {
    public static function TargetMethods() {
        return [null, 'ok'];
    }
}

foreach (['Class15Patch', 'Class16Patch', 'Class17Patch'] as $class) {
    $ref = new ReflectionClass($class);
    $method = $ref->getMethods(ReflectionMethod::IS_STATIC)[0];
    $result = $method->invoke(null);

    if ($class === 'Class15Patch') {
        if ($result !== null) {
            throw new Exception('returned an unexpected result: null');
        }
    } elseif ($class === 'Class16Patch') {
        if (!is_array($result)) {
            throw new Exception('has wrong return type');
        }
    } else {
        if (is_array($result) && in_array(null, $result, true)) {
            throw new Exception('returned an unexpected result: some element was null');
        }
    }
}
?>
--EXPECT--
