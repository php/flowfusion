--TEST--
Translated seed (inferredbugs-csharp: NLog.Web/1)
--FILE--
<?php
declare(strict_types=1);

$items = [];

$cases = [
    [null, false, null],
    ['key', false, null],
    ['key', true, 'string'],
    ['key', true, 1],
    ['key', true, 1.5],
    ['key', true, new DateTimeImmutable()],
    ['key', true, ['a', 1]],
    ['key', true, ['Item1' => 'value'], 'key.Item1'],
    ['key', true, ['Item1' => ['Item1' => 1]], 'key.Item1.Item1'],
];

foreach ($cases as $case) {
    $itemKey = $case[0];
    $hasNested = $case[1];
    $value = $case[2];
    $variable = $case[3] ?? 'key';

    $items = [];
    if ($itemKey !== null) {
        $items[$itemKey] = $value;
    }

    $result = '';
    if ($variable !== null && array_key_exists('key', $items)) {
        $current = $items['key'];
        if ($hasNested) {
            foreach (explode('.', $variable) as $part) {
                if (is_array($current) && array_key_exists($part, $current)) {
                    $current = $current[$part];
                } elseif (is_array($current) && isset($current[$part])) {
                    $current = $current[$part];
                } else {
                    $current = null;
                    break;
                }
            }
        }
        if ($current !== null) {
            if ($current instanceof DateTimeInterface) {
                $result = $current->format(DATE_ATOM);
            } elseif (is_array($current)) {
                $result = 'Array';
            } elseif (is_object($current)) {
                $result = method_exists($current, '__toString') ? (string)$current : get_class($current);
            } else {
                $result = (string)$current;
            }
        }
    }
}

echo "";
?>
--EXPECT--
