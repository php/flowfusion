--TEST--
Translated seed (inferredbugs-java: J2V8/6)
--FILE--
<?php

$runtime = new class {
    private static $counter = 0;

    public function __construct() {
        self::$counter++;
    }

    public static function create() {
        return new self();
    }

    public static function getCounter() {
        return self::$counter;
    }

    public function execute($script) {
        eval($script);
    }

    public function terminate() {
        self::$counter--;
    }
};

$runtime1 = $runtime::create();
$runtime2 = $runtime::create();

$runtime1->execute('echo "Runtime 1\n";');
$runtime2->execute('echo "Runtime 2\n";');

$runtime1->terminate();
$runtime2->terminate();

echo "Active runtimes: " . $runtime::getCounter() . "\n";
--EXPECT--
