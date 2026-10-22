--TEST--
Translated seed (inferredbugs-csharp: linq2db.EntityFrameworkCore/1)
--FILE--
<?php
$context = null;
try {
    if ($context === null) {
        throw new InvalidArgumentException('context');
    }
} catch (InvalidArgumentException $e) {
}

$source = [[], ['x' => 1], null, ['x' => 9223372036854775807], ['x' => -1]];
$batchSize = 0;

$chunks = [];
if ($batchSize > 0) {
    for ($i = 0, $n = count($source); $i < $n; $i += $batchSize) {
        $chunks[] = array_slice($source, $i, $batchSize);
    }
} else {
    $chunks[] = $source;
}

$target = new ArrayObject();
foreach ($chunks as $chunk) {
    foreach ($chunk as $row) {
        if ($row !== null) {
            $target[] = $row;
        }
    }
}

foreach ($target as $row) {
    if (is_array($row) && array_key_exists('x', $row)) {
        $v = $row['x'];
        if (is_int($v) && $v < 0) {
            $row['x'] = abs($v);
        }
    }
}

echo count($target), PHP_EOL;
--EXPECT--
