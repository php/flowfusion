--TEST--
Translated seed (inferredbugs-csharp: MxNet.Sharp/2)
--FILE--
<?php
$data = range(0, 9);
$label = range(100, 109);
$batchSize = 32;
$numData = count($data);
$cursor = -$batchSize;

$cursor += $batchSize;
if ($cursor < $numData) {
    $start = (int)$cursor;
    $end = (int)$cursor + $batchSize;
    if ($end > $numData) {
        $end = $numData;
    }
    $dataSlice = array_slice($data, $start, $end - $start);
    $idx = [];
    for ($i = $start; $i < $end; $i++) {
        $idx[] = $i;
    }
    $labelEnd = (int)$cursor + $batchSize;
    if ($labelEnd >= $numData) {
        $labelEnd = $numData;
    }
    $labelSlice = array_slice($label, $start, $labelEnd - $start);
    $labelSlice = array_values($labelSlice);
}
?>
--EXPECT--
