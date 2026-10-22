--TEST--
Translated seed (inferredbugs-csharp: OpenCoreMMO/1)
--FILE--
<?php
$areas = [
    'AREA_CIRCLE3x3' => [[1,1,1],[1,1,1],[1,1,1]],
    'AREA_SQUARE1x1' => [[1]],
];

$inputs = ['AREA_CIRCLE3x3', 'AREA_SQUARE1x1', 'UNKNOWN'];
foreach ($inputs as $areaType) {
    $result = $areas[$areaType] ?? null;
    if ($result !== null) {
        foreach ($result as $row) {
            foreach ($row as $cell) {
                $x = $cell;
            }
        }
    }
}
?>
--EXPECT--
