--TEST--
Translated seed (inferredbugs-csharp: NLog.Web/2)
--FILE--
<?php
$items = [
    'key' => 'string',
    'key2' => 1,
    'key3' => 1.5,
    'key4' => new DateTime(),
    'key5' => ['a', 1],
    'nested1' => ['Item1' => 'value'],
    'nested2' => ['Item1' => ['Item1' => 1]],
];

$cases = [
    [null, false],
    ['', false],
    ['missing', false],
    ['key', false],
    ['key2', false],
    ['key3', false],
    ['key4', false],
    ['key5', false],
    ['key', true],
    ['key2', true],
    ['nested1.Item1', true],
    ['nested2.Item1.Item1', true],
];

foreach ($cases as [$variable, $nested]) {
    $result = '';
    if ($variable !== null && $variable !== '') {
        if ($nested) {
            $parts = explode('.', $variable);
            $value = $items[$parts[0]] ?? null;
            for ($i = 1; $i < count($parts) && $value !== null; $i++) {
                $p = $parts[$i];
                if (is_array($value) && array_key_exists($p, $value)) {
                    $value = $value[$p];
                } elseif (is_array($value) && ctype_digit($p) && array_key_exists((int)$p, $value)) {
                    $value = $value[(int)$p];
                } else {
                    $value = null;
                }
            }
            if ($value !== null) {
                $result = (string)$value;
            }
        } elseif (array_key_exists($variable, $items)) {
            $result = (string)$items[$variable];
        }
    }
    echo $result . "\n";
}
?>
--EXPECT--
