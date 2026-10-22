--TEST--
Translated seed (inferredbugs-csharp: NLog.Web/4)
--FILE--
<?php
$items = [];
$items['key'] = 'string';
echo (string)($items['key'] ?? '');
echo PHP_EOL;

$items['key'] = 1;
echo (string)($items['key'] ?? '');
echo PHP_EOL;

$items['key'] = 1.5;
echo (string)($items['key'] ?? '');
echo PHP_EOL;

$items['key'] = new DateTimeImmutable();
echo $items['key']->format(DateTimeInterface::ATOM);
echo PHP_EOL;

$items['key'] = ['Item1' => 'a', 0 => 1];
echo (string)($items['key']['Item1'] ?? '');
echo PHP_EOL;

$items['key'] = ['Item1' => ['Item1' => 1]];
echo (string)($items['key']['Item1']['Item1'] ?? '');
echo PHP_EOL;

$items = [];
echo (string)($items['missing'] ?? '');
echo PHP_EOL;

$items['key'] = null;
echo (string)($items['key'] ?? '');
echo PHP_EOL;
?>
--EXPECT--
