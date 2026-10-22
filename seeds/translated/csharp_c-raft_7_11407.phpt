--TEST--
Translated seed (inferredbugs-csharp: c-raft/7)
--FILE--
<?php
$position = ['x' => 1.25, 'y' => 2.5, 'z' => -3.75];
$bboxMinY = 0.0;
$bboxMaxY = 1.0;
$centerY = ($bboxMinY + $bboxMaxY) / 2.0;
$abs = ['x' => $position['x'], 'y' => $centerY, 'z' => $position['z']];
$chunkX = (int)floor($position['x'] / 16);
$chunkZ = (int)floor($position['z'] / 16);
$chunks = [];
$chunk = $chunks[$chunkX][$chunkZ] ?? null;
if ($chunk === null) {
    exit(0);
}
echo $chunk;
?>
--EXPECT--
