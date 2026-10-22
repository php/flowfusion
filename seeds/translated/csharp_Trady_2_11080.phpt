--TEST--
Translated seed (inferredbugs-csharp: Trady/2)
--FILE--
<?php
declare(strict_types=1);

$asset1 = [
    ['dt' => 1, 'open' => 10.0],
    ['dt' => 2, 'open' => 11.0],
    ['dt' => 3, 'open' => 12.0],
];
$asset2 = [
    ['dt' => 1, 'open' => 20.0],
    ['dt' => 2, 'open' => 19.0],
    ['dt' => 3, 'open' => 18.0],
];

$weightings = [
    0 => 1,
    1 => 3,
];

$principal = 1000.0;
$premium = 1.0;
$totalWeight = array_sum($weightings);
$assetCashMap = [];
$transactions = [];

foreach ($weightings as $i => $w) {
    $assetCashMap[$i] = $principal * $w / $totalWeight;
}

for ($i = 0; $i < count($weightings); $i++) {
    $asset = $i === 0 ? $asset1 : $asset2;
    $cash = $assetCashMap[$i];
    $count = count($asset);

    for ($j = 0; $j < $count - 1; $j++) {
        $next = $asset[$j + 1];
        if (($j % 2) === 0) {
            $quantity = (int)floor(($cash - $premium) / $next['open']);
            $cashOut = $next['open'] * $quantity + $premium;
            $assetCashMap[$i] -= $cashOut;
            $transactions[] = [$i, $next['dt'], 'buy', $quantity, $cashOut];
        } else {
            $last = null;
            for ($k = count($transactions) - 1; $k >= 0; $k--) {
                if ($transactions[$k][0] === $i) {
                    $last = $transactions[$k];
                    break;
                }
            }
            if ($last !== null) {
                $cashIn = $next['open'] * $last[3] - $premium;
                $assetCashMap[$i] += $cashIn;
                $transactions[] = [$i, $next['dt'], 'sell', $last[3], $cashIn];
            }
        }
    }
}

echo "";
?>
--EXPECT--
