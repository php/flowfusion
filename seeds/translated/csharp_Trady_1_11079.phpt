--TEST--
Translated seed (inferredbugs-csharp: Trady/1)
--FILE--
<?php
declare(strict_types=1);

$assets = [
    [
        ['dt' => '2020-01-01', 'open' => 10.0],
        ['dt' => '2020-01-02', 'open' => 11.0],
        ['dt' => '2020-01-03', 'open' => 12.0],
    ],
    [
        ['dt' => '2020-01-01', 'open' => 20.0],
        ['dt' => '2020-01-02', 'open' => 19.0],
        ['dt' => '2020-01-03', 'open' => 18.0],
    ],
];

$weightings = [0 => 1, 1 => 2];
$principal = 1000.0;
$premium = 1.0;

$totalWeight = array_sum($weightings);
$cashMap = [];
foreach ($weightings as $i => $w) {
    $cashMap[$i] = $principal * $w / $totalWeight;
}

$transactions = [];

foreach ($assets as $i => $asset) {
    $startIndex = 0;
    $endIndex = count($asset) - 2;
    for ($j = $startIndex; $j <= $endIndex; $j++) {
        $next = $asset[$j + 1];
        if (!isset($transactions[$i]) || end($transactions[$i])['type'] !== 'buy') {
            $cash = $cashMap[$i];
            $quantity = (int)floor(($cash - $premium) / $next['open']);
            $cashOut = $next['open'] * $quantity + $premium;
            $cashMap[$i] -= $cashOut;
            $transactions[$i][] = [
                'type' => 'buy',
                'index' => $j + 1,
                'dt' => $next['dt'],
                'price' => $next['open'],
                'quantity' => $quantity,
                'cash' => $cashOut,
            ];
        }
        $last = end($transactions[$i]);
        if ($last['type'] !== 'sell') {
            $cashIn = $next['open'] * $last['quantity'] - $premium;
            $plRatio = ($cashIn - $last['cash']) / $last['cash'];
            $cashMap[$i] += $cashIn;
            $transactions[$i][] = [
                'type' => 'sell',
                'index' => $j + 1,
                'dt' => $next['dt'],
                'price' => $next['open'],
                'quantity' => $last['quantity'],
                'cash' => $cashIn,
                'plRatio' => $plRatio,
            ];
        }
    }
}

echo "";
?>
--EXPECT--
