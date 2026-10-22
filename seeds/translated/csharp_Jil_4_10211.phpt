--TEST--
Translated seed (inferredbugs-csharp: Jil/4)
--FILE--
<?php
declare(strict_types=1);

$styles = [
    ['NewtonsoftStyleMillisecondsSinceUnixEpoch', true],
    ['NewtonsoftStyleMillisecondsSinceUnixEpoch', false],
    ['MillisecondsSinceUnixEpoch', true],
    ['MillisecondsSinceUnixEpoch', false],
    ['SecondsSinceUnixEpoch', true],
    ['SecondsSinceUnixEpoch', false],
    ['ISO8601', true],
    ['ISO8601', false],
];

$cache = [];
foreach ($styles as [$style, $allowHashing]) {
    $key = $style . ':' . ($allowHashing ? '1' : '0');
    if (!isset($cache[$key])) {
        $cache[$key] = static function ($input) use ($style, $allowHashing) {
            if ($allowHashing) {
                return md5($style . '|' . $input);
            }
            return sha1($style . '|' . $input);
        };
    }
}

$inputs = [
    '0',
    '1',
    '-1',
    '2147483647',
    '2147483648',
    '9223372036854775807',
    '1970-01-01T00:00:00Z',
    '9999-12-31T23:59:59Z',
];

foreach ($cache as $fn) {
    foreach ($inputs as $input) {
        $fn($input);
    }
}
?>
--EXPECT--
