--TEST--
Translated seed (inferredbugs-csharp: Jil/3)
--FILE--
<?php
$styles = [
    ['newtonsoft_ms', true],
    ['newtonsoft_ms', false],
    ['millis', true],
    ['millis', false],
    ['seconds', true],
    ['seconds', false],
    ['iso8601', true],
    ['iso8601', false],
];

$cache = [];
foreach ($styles as [$style, $hashing]) {
    $key = $style . ':' . ($hashing ? '1' : '0');
    if (!isset($cache[$key])) {
        $cache[$key] = static function ($s) use ($style, $hashing) {
            if ($style === 'iso8601') {
                $dt = new DateTimeImmutable($s);
                return $hashing ? $dt->format('c') : $dt->format(DateTimeInterface::ATOM);
            }
            $n = (int)$s;
            if ($style === 'seconds') {
                $n = $hashing ? $n : (int)($n / 1);
                return gmdate('c', $n);
            }
            $sec = (int) floor($n / 1000);
            $ms = $n % 1000;
            return sprintf('%s.%03dZ', gmdate('Y-m-d\TH:i:s', $sec), $ms);
        };
    }
    $f = $cache[$key];
    $f('0');
    $f('1');
    $f('1700000000123');
    $f('2020-01-02T03:04:05+00:00');
}
?>
--EXPECT--
