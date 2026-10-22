--TEST--
Translated seed (inferredbugs-csharp: Jil/7)
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
$errors = [];

foreach ($styles as [$format, $allowHashing]) {
    $key = $format . '|' . ($allowHashing ? '1' : '0');

    if (!array_key_exists($key, $cache)) {
        $cache[$key] = function (string $input) use ($format, $allowHashing) {
            if ($allowHashing) {
                $input = hash('sha256', $input, false);
            }

            return match ($format) {
                'NewtonsoftStyleMillisecondsSinceUnixEpoch', 'MillisecondsSinceUnixEpoch' => (int) floor(((int) $input) / 1000),
                'SecondsSinceUnixEpoch' => (int) $input,
                'ISO8601' => strtotime($input),
                default => null,
            };
        };
    }

    $fn = $cache[$key];

    $inputs = $allowHashing
        ? ['0', '1', '9223372036854775807', '0001-01-01T00:00:00Z']
        : ['0', '1', '2147483647', '1970-01-01T00:00:00Z'];

    foreach ($inputs as $input) {
        $result = $fn($input);
        if ($result === false) {
            $errors[] = $key . ':' . $input;
        }
    }
}

echo "ok\n";
--EXPECT--
