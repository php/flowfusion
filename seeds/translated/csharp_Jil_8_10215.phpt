--TEST--
Translated seed (inferredbugs-csharp: Jil/8)
--FILE--
<?php
declare(strict_types=1);

$styles = [
    ['newtonsoft_ms', true],
    ['newtonsoft_ms', false],
    ['millisecond', true],
    ['millisecond', false],
    ['second', true],
    ['second', false],
    ['iso8601', true],
    ['iso8601', false],
];

foreach ($styles as [$style, $allowHashing]) {
    $cache = [];
    $thunk = null;

    if ($thunk !== null) {
        continue;
    }

    if (!isset($cache['init'])) {
        $cache['init'] = true;
        if ($thunk !== null) {
            continue;
        }

        $thunk = static function ($input) use ($style, $allowHashing) {
            if ($style === 'iso8601') {
                $dt = new DateTimeImmutable($input);
                return $allowHashing ? $dt->format(DateTimeInterface::ATOM) : $dt->format('c');
            }

            $n = (int)$input;
            if ($style === 'newtonsoft_ms' || $style === 'millisecond') {
                $sec = intdiv($n, 1000);
                $ms = $n % 1000;
                return $allowHashing ? [$sec, $ms] : "{$sec}.{$ms}";
            }

            return $allowHashing ? intdiv($n, 1) : (string)$n;
        };
    }

    $samples = [
        '0',
        '1',
        '-1',
        '253402300799',
        '1970-01-01T00:00:00+00:00',
    ];

    foreach ($samples as $s) {
        $thunk($s);
    }
}
--EXPECT--
