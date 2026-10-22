--TEST--
Translated seed (inferredbugs-csharp: Trady/3)
--FILE--
<?php
class Ohlcv {
    public $DateTime;
    public $Open;
    public function __construct($dt, $open) { $this->DateTime = $dt; $this->Open = $open; }
}

$asset1 = [new Ohlcv(1, 10), new Ohlcv(2, 11), new Ohlcv(3, 12)];
$asset2 = [new Ohlcv(1, 20), new Ohlcv(2, 19), new Ohlcv(3, 18)];
$weightings = [$asset1 => 1, $asset2 => 2];

$principal = 1000.0;
$premium = 1.0;

$totalWeight = array_sum($weightings);
$assetCashMap = [];
foreach ($weightings as $asset => $w) $assetCashMap[$asset] = $principal * $w / $totalWeight;

$transactions = [];
foreach ($weightings as $asset => $_) {
    $cash = $assetCashMap[$asset];
    $next = $asset[1];
    $quantity = (int)floor(($cash - $premium) / $next->Open);
    $cashOut = $next->Open * $quantity + $premium;
    $assetCashMap[$asset] -= $cashOut;
    $transactions[] = [$asset, $next->DateTime, 'buy', $quantity, $cashOut];
    $last = end($transactions);
    $cashIn = $asset[2]->Open * $last[3] - $premium;
    $assetCashMap[$asset] += $cashIn;
    $transactions[] = [$asset, $asset[2]->DateTime, 'sell', $last[3], $cashIn];
}

echo count($transactions), PHP_EOL;
?>
--EXPECT--
