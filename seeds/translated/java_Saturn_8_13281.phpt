--TEST--
Translated seed (inferredbugs-java: Saturn/8)
--FILE--
<?php
class SaturnAutoBasic {
    private static $timeout = 15;
    private static $namespace = 'it-saturn-java.vip.vip.com';
    private static $saturnExecutorList = [];
    private static $nestedZkUtils;

    private static function initSysEnv() {
        putenv("namespace=" . self::$namespace);
        putenv("saturn.stdout=true");
    }

    private static function startExecutorList($count) {
        for ($i = 0; $i < $count; $i++) {
            self::startOneNewExecutorList();
        }
        usleep(2000000);
    }

    private static function startOneNewExecutorList() {
        $size = count(self::$saturnExecutorList);
        $executorName = "executorName" . $size;
        self::$saturnExecutorList[] = $executorName;
        usleep(1000000);
    }

    public static function run() {
        self::initSysEnv();
        self::startExecutorList(5);
        $executorCount = count(self::$saturnExecutorList);
        echo "Started $executorCount executors\n";
    }
}

SaturnAutoBasic::run();
--EXPECT--
