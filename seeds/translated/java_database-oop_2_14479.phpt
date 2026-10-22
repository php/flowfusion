--TEST--
Translated seed (inferredbugs-java: database-oop/2)
--FILE--
<?php

$DEFAULT_DB_PATH = 'database.sqlite';
$CON_MAX = 2;
$CON_MIN = 1;
$CON_STEP = 1;
$REFRESH_CON_POOL = false;
$USE_CONNECT_POOL = true;
$CON_TIMEOUT = 500000;
$idleConList = [];
$runConList = [];

function addConnection($dbPath, $num) {
    global $idleConList;
    for ($i = 0; $i < $num; $i++) {
        $idleConList[] = createBaseConnection($dbPath);
    }
}

function createBaseConnection($dbPath) {
    // Simulating a database connection
    return ['dbPath' => $dbPath, 'created' => microtime(true)];
}

function getConnection($dbPath) {
    global $idleConList, $runConList, $CON_MAX;
    $currCon = null;
    if (!empty($idleConList)) {
        $currCon = array_shift($idleConList);
        $runConList[] = $currCon;
    }
    if ($currCon == null || !isset($currCon['dbPath'])) {
        $currCon = createBaseConnection($dbPath);
        $runConList[] = $currCon;
    }
    return $currCon;
}

function checkAllIdleConnection() {
    global $idleConList;
    foreach ($idleConList as $key => $con) {
        if (microtime(true) - $con['created'] > 100) {
            unset($idleConList[$key]);
        }
    }
}

function checkConnectionBox($dbPath) {
    global $CON_MAX, $CON_MIN, $idleConList, $runConList, $CON_STEP;
    $addNum = 0;
    if (count($idleConList) === 0) {
        $addNum = $CON_MAX - count($runConList);
        if ($addNum <= 0) return false;
    } elseif (count($idleConList) < $CON_MIN) {
        $addNum = $CON_MAX - count($idleConList) - count($runConList);
        if ($addNum <= 0) return false;
    }
    $addNum = min($addNum, $CON_STEP);
    addConnection($dbPath, $addNum);
    return true;
}

$USE_CONNECT_POOL && addConnection($DEFAULT_DB_PATH, $CON_MIN);
$con = getConnection($DEFAULT_DB_PATH);
checkConnectionBox($DEFAULT_DB_PATH);
checkAllIdleConnection();
--EXPECT--
