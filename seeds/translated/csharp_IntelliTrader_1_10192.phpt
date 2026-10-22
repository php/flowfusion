--TEST--
Translated seed (inferredbugs-csharp: IntelliTrader/1)
--FILE--
<?php
$config = ['PollingInterval' => 1, 'SignalPeriod' => 7];
$signalName = "X";
$taskName = "TradingViewCryptoSignalPollingTimedTask [$signalName]";
$signals = null;

$interval = intdiv($config['PollingInterval'] * 1000, 1);

$signals = $signals ?? [];
$avg = $signals ? array_sum($signals) / count($signals) : null;

echo $taskName, "\n", $interval, "\n", $config['SignalPeriod'], "\n";
if ($avg !== null) echo $avg, "\n";
?>
--EXPECT--
