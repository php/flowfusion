--TEST--
Translated seed (inferredbugs-csharp: NLog.Web/3)
--FILE--
<?php
$items = [];
$items['key'] = 'string';
echo (string)($items['key'] ?? '');
$items['key'] = 1;
echo (string)($items['key'] ?? '');
$items['key'] = 1.5;
echo (string)($items['key'] ?? '');
$items['key'] = new DateTime();
echo $items['key']->format(DateTimeInterface::ATOM);
$items['key'] = ['a', 1];
echo implode(',', $items['key']);
$items = [];
$items['key'] = ['value'];
echo (string)($items['key'][0] ?? '');
$items = [];
$items['key'] = [[1]];
echo (string)($items['key'][0][0] ?? '');
?>
--EXPECT--
