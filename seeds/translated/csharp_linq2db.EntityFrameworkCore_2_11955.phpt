--TEST--
Translated seed (inferredbugs-csharp: linq2db.EntityFrameworkCore/2)
--FILE--
<?php
declare(strict_types=1);

$context = null;
$source = [0, 1, 2];

try {
    $batchSize = 0;
    $chunks = array_chunk($source, $batchSize);
    foreach ($chunks as $chunk) {
        foreach ($chunk as $v) {
            $sink[] = $v;
        }
    }
} catch (Throwable $e) {
}

$target = new ArrayObject([]);
$it = $target->getIterator();
foreach ($source as $k => $v) {
    $target[$k] = $v;
}

foreach ($target as $v) {
    $sink[] = $v;
}
?>
--EXPECT--
