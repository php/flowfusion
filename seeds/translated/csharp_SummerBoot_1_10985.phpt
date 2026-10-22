--TEST--
Translated seed (inferredbugs-csharp: SummerBoot/1)
--FILE--
<?php
$items = [0, 1, 2];
$map = [];
foreach ($items as $i) {
    $map[(string)$i] = ["id" => $i, "active" => 1, "createOn" => null, "lastUpdateOn" => null];
}

$softDelete = true;
$autoAddCreateOn = true;
$autoUpdateLastUpdateOn = true;

foreach ($items as $i) {
    $row =& $map[(string)$i];
    if ($autoAddCreateOn) {
        $row["createOn"] = microtime(true);
        $row["active"] = 1;
    }
    if ($autoUpdateLastUpdateOn) {
        $row["lastUpdateOn"] = microtime(true);
    }
    if ($softDelete && ($i % 2 === 0)) {
        $row["active"] = 0;
    }
    $row["id"] = (int)$row["id"];
    unset($row);
}

$sum = 0;
foreach ($map as $row) {
    $sum += $row["active"] + (int)$row["id"];
}

echo $sum, PHP_EOL;
?>
--EXPECT--
