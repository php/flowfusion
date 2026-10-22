--TEST--
Translated seed (inferredbugs-csharp: GB28181.Solution/1)
--FILE--
<?php
$mainService = new class {
    public function run(): void {}
};
$mainService->run();
?>
--EXPECT--
