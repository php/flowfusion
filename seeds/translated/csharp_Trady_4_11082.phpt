--TEST--
Translated seed (inferredbugs-csharp: Trady/4)
--FILE--
<?php
$assets = [
    [
        ['dt' => 1, 'open' => 10.0],
        ['dt' => 2, 'open' => 11.0],
        ['dt' => 3, 'open' => 12.0],
    ],
    [
        ['dt' => 1, 'open' => 20.0],
        ['dt' => 2, 'open' => 21.0],
        ['dt' => 3, 'open' => 22.0],
    ],
];

$weights = [3, 1];
$principal = 1000.0;
$premium = 1.0;
$flatExchangeFee = 0.001;
$buyCompleteBaseCurrencies = true;

$totalWeight = array_sum($weights);
$cash = [];
foreach ($assets as $i => $asset) {
    $cash[$i] = $principal * $weights[$i] / $totalWeight;
}

$transactions = [];

foreach ($assets as $i => $asset) {
    for ($j = 0; $j < count($asset) - 1; $j++) {
        $candle = $asset[$j];
        $next = $asset[$j + 1];

        if (!isset($transactions[$i])) {
            $available = $cash[$i];
            $baseQty = ($available - $premium) / $next['open'];
            if ($buyCompleteBaseCurrencies) {
                $baseQty = floor($baseQty);
            }
            $quoteQty = $next['open'] * $baseQty + $premium;
            $baseQty -= $flatExchangeFee * $baseQty;
            $cash[$i] -= $quoteQty;
            $transactions[$i] = ['qty' => $baseQty, 'abs' => $quoteQty];
        } else {
            $baseQty = $transactions[$i]['qty'];
            $quoteQty = $next['open'] * $baseQty - $premium;
            $quoteQty -= $flatExchangeFee * $quoteQty;
            $plRatio = ($quoteQty - $transactions[$i]['abs']) / $transactions[$i]['abs'];
            $cash[$i] += $quoteQty;
            $transactions[$i] = ['qty' => $baseQty, 'abs' => $quoteQty, 'pl' => $plRatio];
        }
    }
}

echo "";
?>
--EXPECT--
