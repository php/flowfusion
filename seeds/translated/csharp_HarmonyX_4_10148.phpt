--TEST--
Translated seed (inferredbugs-csharp: HarmonyX/4)
--FILE--
<?php
$cases = [
    'returned an unexpected result: null',
    'has wrong return type',
    'returned an unexpected result: some element was null',
];

foreach ($cases as $msg) {
    try {
        throw new Exception($msg);
    } catch (Exception $e) {
        $inner = new Exception($e->getMessage());
        $ok = strpos($inner->getMessage(), $msg) !== false;
    }
}
?>
--EXPECT--
