--TEST--
Translated seed (inferredbugs-csharp: StratisBitcoinFullNode/6)
--FILE--
<?php
$txs = [];
for ($i = 0; $i < 10; $i++) {
    $txs[$i] = ['id' => $i + 1, 'parents' => [], 'fee' => 0, 'time' => 0, 'anc' => 1, 'desc' => 1];
}
$txs[0]['fee'] = 10000; $txs[0]['desc'] = 1;
$txs[1]['fee'] = 20000; $txs[1]['desc'] = 1;
$txs[2]['fee'] = 0; $txs[2]['desc'] = 1;
$txs[3]['fee'] = 15000; $txs[3]['desc'] = 1;
$txs[4]['fee'] = 10000; $txs[4]['time'] = 1; $txs[4]['desc'] = 1;
$txs[5]['fee'] = 0; $txs[5]['desc'] = 1;
$txs[6]['parents'] = [5]; $txs[6]['fee'] = 2000000; $txs[6]['anc'] = 2; $txs[6]['desc'] = 2;
$txs[7]['parents'] = [6]; $txs[7]['fee'] = 0; $txs[7]['time'] = 2; $txs[7]['anc'] = 3; $txs[7]['desc'] = 3;
$txs[8]['parents'] = [6]; $txs[8]['fee'] = 0; $txs[8]['time'] = 3; $txs[8]['anc'] = 3; $txs[8]['desc'] = 3;
$txs[9]['parents'] = [7, 8]; $txs[9]['fee'] = 200000; $txs[9]['time'] = 4; $txs[9]['anc'] = 4; $txs[9]['desc'] = 4;

$cmp = function ($a, $b) use ($txs) {
    $A = $txs[$a - 1]; $B = $txs[$b - 1];
    if ($A['desc'] !== $B['desc']) return $B['desc'] <=> $A['desc'];
    if ($A['fee'] !== $B['fee']) return $B['fee'] <=> $A['fee'];
    if ($A['time'] !== $B['time']) return $B['time'] <=> $A['time'];
    return $a <=> $b;
};

$pool = [1,2,3,4,5,6,7,8,9,10];
usort($pool, $cmp);
$pool = array_values($pool);
$remove = function (&$pool, $id, $txs) {
    $gone = [$id => true];
    do {
        $changed = false;
        foreach ($pool as $x) {
            if (isset($gone[$x])) continue;
            foreach ($txs[$x - 1]['parents'] as $p) {
                if (isset($gone[$p])) { $gone[$x] = true; $changed = true; break; }
            }
        }
    } while ($changed);
    $pool = array_values(array_filter($pool, fn($x) => !isset($gone[$x])));
};

$remove($pool, 7, $txs);
$remove($pool, 8, $txs);
$remove($pool, 10, $txs);
usort($pool, $cmp);
echo count($pool), "\n";
?>
--EXPECT--
