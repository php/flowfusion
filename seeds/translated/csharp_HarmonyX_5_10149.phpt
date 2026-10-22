--TEST--
Translated seed (inferredbugs-csharp: HarmonyX/5)
--FILE--
<?php
$cases = [
    ['returned an unexpected result: null', null],
    ['has wrong return type', 123],
    ['returned an unexpected result: some element was null', [1, null, 3]],
];

foreach ($cases as [$needle, $value]) {
    $ok = false;
    try {
        if ($value === null) {
            throw new RuntimeException("returned an unexpected result: null");
        }
        if (is_int($value)) {
            throw new RuntimeException("has wrong return type");
        }
        foreach ($value as $item) {
            if ($item === null) {
                throw new RuntimeException("returned an unexpected result: some element was null");
            }
        }
        $ok = true;
    } catch (Throwable $e) {
        if (strpos($e->getMessage(), $needle) !== false) {
            $ok = true;
        }
    }
    if (!$ok) {
        exit(1);
    }
}
?>
--EXPECT--
