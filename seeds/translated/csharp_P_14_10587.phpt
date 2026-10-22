--TEST--
Translated seed (inferredbugs-csharp: P/14)
--FILE--
<?php
$buf = [];
for ($i = 0; $i < 20000; $i++) {
    $buf[] = str_repeat(chr($i & 255), ($i % 7) + 1);
}
$map = [];
for ($i = 0; $i < 5000; $i++) {
    $k = "k" . ($i % 97);
    $map[$k] = ($map[$k] ?? 0) + 1;
}
$set = [];
foreach ($buf as $s) {
    $set[$s] = true;
}
$needle = $buf[1234];
unset($set[$needle]);
$set[$needle] = true;
$queue = new SplQueue();
for ($i = 0; $i < 3000; $i++) {
    $queue->enqueue([$i, $buf[$i]]);
}
while (!$queue->isEmpty()) {
    [$n, $s] = $queue->dequeue();
    if (($n & 255) === 0) {
        $map[$s[0]] = ($map[$s[0]] ?? 0) ^ $n;
    }
}
$h = 0;
foreach ($map as $v) {
    $h ^= $v;
}
echo $h . "\n";
?>
--EXPECT--
