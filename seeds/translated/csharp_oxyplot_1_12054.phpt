--TEST--
Translated seed (inferredbugs-csharp: oxyplot/1)
--FILE--
<?php
$items = [
    ['Label' => 'Apples', 'Value1' => 37, 'Value2' => 12, 'Value3' => 19],
    ['Label' => 'Pears', 'Value1' => 7, 'Value2' => 21, 'Value3' => 9],
    ['Label' => 'Bananas', 'Value1' => 23, 'Value2' => 2, 'Value3' => 29],
];

$bins = [];
for ($i = 0; $i < 20; $i++) $bins[$i] = ['Label' => (string)$i, 'Value' => 0];
for ($i = 0; $i < 1000; $i++) $bins[random_int(0, 19)]['Value']++;

$data = [
    'simple' => [
        'stacked' => false,
        'horizontal' => false,
        'series' => [
            ['title' => 'BarSeries 1', 'values' => [25, 37, 18, 40]],
            ['title' => 'BarSeries 2', 'values' => [12, 14, 20, 26]],
        ],
        'categories' => ['Category A', 'Category B', 'Category C', 'Category D'],
    ],
    'stacked_horizontal' => [
        'stacked' => true,
        'horizontal' => true,
        'series' => [
            ['title' => 'BarSeries 1', 'values' => [25, 37, 18, 40]],
            ['title' => 'BarSeries 2', 'values' => [12, 14, 20, 26]],
        ],
        'categories' => ['Category A', 'Category B', 'Category C', 'Category D'],
    ],
    'log_axis' => [
        'baseValue' => 0.1,
        'values' => [25, 37, 18, 40],
        'categories' => ['Category A', 'Category B', 'Category C', 'Category D'],
    ],
    'bar_series' => [
        'items' => $items,
        'series' => [
            ['title' => '2009', 'field' => 'Value1', 'fill' => [255, 78, 154, 6]],
            ['title' => '2010', 'field' => 'Value2', 'fill' => [255, 200, 141, 0]],
            ['title' => '2011', 'field' => 'Value3', 'fill' => [255, 204, 0, 0]],
        ],
    ],
    'histogram' => [
        'bins' => $bins,
    ],
];

echo json_encode($data, JSON_UNESCAPED_SLASHES), PHP_EOL;
?>
--EXPECT--
