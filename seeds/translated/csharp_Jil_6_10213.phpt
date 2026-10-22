--TEST--
Translated seed (inferredbugs-csharp: Jil/6)
--FILE--
<?php
declare(strict_types=1);

final class Cache {
    public static ?array $thunk = null;
    public static ?Throwable $exceptionDuringBuild = null;
    public static function get(bool $allowHashing, string $format): array {
        if (self::$thunk !== null) return self::$thunk;
        self::$thunk = [$allowHashing, $format, hash('sha256', $format . ($allowHashing ? '1' : '0'))];
        return self::$thunk;
    }
}

$formats = ['newtonsoft_ms', 'millisecond', 'second', 'iso8601'];
foreach ([true, false] as $allowHashing) {
    foreach ($formats as $format) {
        Cache::get($allowHashing, $format);
        Cache::get($allowHashing, $format);
    }
}
echo "ok\n";
?>
--EXPECT--
