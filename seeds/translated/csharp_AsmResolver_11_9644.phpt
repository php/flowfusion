--TEST--
Translated seed (inferredbugs-csharp: AsmResolver/11)
--FILE--
<?php

class SampleClass {
    public static function main() {
        $outputPath = getenv('HOME') . '/Desktop/output.php';
        file_put_contents($outputPath, '<?php echo "Hello, world!"; ?>');
    }
}

SampleClass::main();
--EXPECT--
