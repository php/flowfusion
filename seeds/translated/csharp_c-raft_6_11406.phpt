--TEST--
Translated seed (inferredbugs-csharp: c-raft/6)
--FILE--
<?php
$w = 0;
$t = 0;
for ($i = 0; $i < 24010; $i++) {
    $t++;
    $w++;
    if ($t === 24000) $t = 0;
    if (($w % 10) === 0) {
        $a = [];
        for ($j = 0; $j < 3; $j++) $a[] = $j;
        foreach ($a as $k => $v) {
            if ($k === 1) unset($a[$k]);
        }
        $a = array_values($a);
    }
    if (($w % 20) === 0) {
        $q = new SplQueue();
        for ($j = 0; $j < 5; $j++) $q->enqueue($j);
        $n = $q->count();
        for ($j = 0; $j < $n; $j++) {
            if (!$q->isEmpty()) $q->dequeue();
        }
    }
}
echo "";
?>
--EXPECT--
