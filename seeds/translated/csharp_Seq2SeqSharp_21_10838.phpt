--TEST--
Translated seed (inferredbugs-csharp: Seq2SeqSharp/21)
--FILE--
<?php
$src = [[], ['a', 'b', 'c', 'd', 'e']];
$tgt = [['<s>'], ['</s>']];
$beam = [];
for ($i = 0; $i < 3; $i++) {
    $step = [];
    foreach ($src as $k => $s) {
        $len = count($s);
        $step[$k] = [$len > 2 ? array_slice($s, 0, 2) : $s, $len];
    }
    $beam[] = $step;
    if (count($src[1]) > 4) {
        $src[1] = array_slice($src[1], 0, 4);
    }
}
$out = [];
foreach ($beam as $step) {
    foreach ($step as $k => [$tokens, $len]) {
        $out[$k][] = implode(' ', $tokens) . ':' . $len;
    }
}
echo implode("\n", array_map(fn($x) => implode('|', $x), $out)), "\n";
--EXPECT--
