--TEST--
Translated seed (inferredbugs-csharp: NLog.Web/5)
--FILE--
<?php
declare(strict_types=1);

$items = [];

$values = [
    'string',
    1,
    1.5,
    new DateTimeImmutable(),
    ['a', 1],
];

foreach ($values as $v) {
    $items['key'] = $v;
    echo (string)$items['key'], PHP_EOL;
}

$items['key'] = ['Item1' => 'value'];
echo (string)$items['key']['Item1'], PHP_EOL;

$items['key'] = ['Item1' => ['Item1' => 1]];
echo (string)$items['key']['Item1']['Item1'], PHP_EOL;
?>
--EXPECT--
