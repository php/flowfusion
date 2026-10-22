--TEST--
Translated seed (inferredbugs-csharp: Jil/2)
--FILE--
<?php
declare(strict_types=1);

$styles = [
    ['newtonsoft', true],
    ['newtonsoft', false],
    ['milliseconds', true],
    ['milliseconds', false],
    ['seconds', true],
    ['seconds', false],
    ['iso8601', true],
    ['iso8601', false],
];

$cache = [];
foreach ($styles as [$style, $hashing]) {
    $key = $style . ':' . ($hashing ? '1' : '0');
    if (!isset($cache[$key])) {
        $cache[$key] = static function (string $s) use ($style, $hashing) {
            if ($style === 'iso8601') {
                $dt = new DateTimeImmutable($s);
                return $hashing ? $dt->format('c') : $dt->format(DateTimeInterface::ATOM);
            }
            $n = (int)$s;
            if ($style === 'seconds') $n *= 1000;
            if ($style === 'newtonsoft') $n += 62135596800000;
            return $hashing ? (string)($n ^ 0x5a5a5a5a) : (string)$n;
        };
    }
}

$inputs = ['0', '1', '-1', '253402300799', '1970-01-01T00:00:00Z', '9999-12-31T23:59:59+00:00'];
foreach ($cache as $fn) {
    foreach ($inputs as $in) {
        try {
            $fn($in);
        } catch (Throwable $e) {
        }
    }
}
?>
--EXPECT--
