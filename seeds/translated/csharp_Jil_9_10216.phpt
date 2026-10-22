--TEST--
Translated seed (inferredbugs-csharp: Jil/9)
--FILE--
<?php
declare(strict_types=1);

final class Cache {
    public static $thunk = null;
    public static $exceptionDuringBuild = null;
    public static function get(bool $allowHashing, int $format): callable {
        if (self::$thunk !== null) return self::$thunk;
        self::$thunk = function ($reader) use ($allowHashing, $format) {
            return [$allowHashing, $format, is_resource($reader) ? get_resource_type($reader) : gettype($reader)];
        };
        return self::$thunk;
    }
}

$cases = [
    [true, 0],
    [false, 0],
    [true, 1],
    [false, 1],
    [true, 2],
    [false, 2],
    [true, 3],
    [false, 3],
];

foreach ($cases as [$allowHashing, $format]) {
    Cache::$thunk = null;
    Cache::$exceptionDuringBuild = null;
    $f = Cache::get($allowHashing, $format);
    $r = fopen('php://memory', 'r');
    $f($r);
    fclose($r);
}

echo "";
?>
--EXPECT--
