--TEST--
Translated seed (inferredbugs-csharp: Jil/5)
--FILE--
<?php
declare(strict_types=1);

$styles = [
    ['newtonsoft_ms', true],
    ['newtonsoft_ms', false],
    ['ms', true],
    ['ms', false],
    ['sec', true],
    ['sec', false],
    ['iso8601', true],
    ['iso8601', false],
];

$inputs = [
    '0',
    '1',
    '-1',
    '0.0',
    '1.234',
    '9999999999999',
    '1970-01-01T00:00:00Z',
    '1969-12-31T23:59:59Z',
    '0001-01-01T00:00:00+00:00',
    '9999-12-31T23:59:59.999999Z',
    '',
];

foreach ($styles as [$style, $allowHashing]) {
    foreach ($inputs as $s) {
        $v = null;
        if ($style === 'iso8601') {
            $dt = @date_create_immutable($s);
            if ($dt !== false) {
                $v = $dt->format('c');
            }
        } elseif ($style === 'sec') {
            if (is_numeric($s)) {
                $v = (int)$s;
            }
        } elseif ($style === 'ms') {
            if (is_numeric($s)) {
                $v = (int)round(((float)$s) * 1000.0);
            }
        } else {
            if (is_numeric($s)) {
                $v = (int)$s;
                if ($allowHashing) {
                    $v ^= crc32($s);
                }
            }
        }

        if ($v === null && $allowHashing) {
            $v = md5($style . ':' . $s, true);
        }
    }
}
?>
--EXPECT--
