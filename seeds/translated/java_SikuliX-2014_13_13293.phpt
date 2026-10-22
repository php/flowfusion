--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/13)
--FILE--
<?php

class SikuliIDE {
    private static $running = false;
    private static $isRunningFile;

    public static function main() {
        if (self::$running) {
            echo "Terminating on FatalError: IDE already running\n";
            exit(1);
        }

        // Simulating file creation for running lock
        self::$isRunningFile = tempnam(sys_get_temp_dir(), 'sikuli-ide-isrunning');
        self::$running = true;

        register_shutdown_function(function() {
            self::cleanup();
        });

        echo "Sikuli IDE is starting...\n";

        // Simulating IDE functionality
        sleep(1);
        echo "IDE initialized.\n";

        // Simulating running a script
        self::runScript();

        self::$running = false;
    }

    private static function cleanup() {
        if (self::$isRunningFile) {
            unlink(self::$isRunningFile);
        }
        echo "Final cleanup done.\n";
    }

    private static function runScript() {
        echo "Running script...\n";
        // Edge case: Simulating a large loop that could trigger memory issues
        for ($i = 0; $i < 1000000; $i++) {
            // Simulating memory consumption
            $arr = str_repeat('x', 1024 * 1024);
        }
        echo "Script execution completed.\n";
    }
}

SikuliIDE::main();
--EXPECT--
