--TEST--
Translated seed (inferredbugs-csharp: P/20)
--FILE--
<?php
$queue = new SplQueue();
$queue->enqueue(['e' => 'x', 'a' => null]);
$queue->enqueue(['e' => 'y', 'a' => 1]);
$queue->enqueue(['e' => 'z', 'a' => '3']);

$receiveSet = [];
$receiveSet[0] = true;
$receiveSet['0'] = true;
$receiveSet[null] = true;

while (!$queue->isEmpty()) {
    $item = $queue->dequeue();
    $key = $item['a'];
    if (isset($receiveSet[$key]) || array_key_exists($key, $receiveSet)) {
        $v = (int)$item['a'];
        $arr = [$v => $item['e']];
        $arr[(string)$v] = $item['e'];
        foreach ($arr as $k => $val) {
            $tmp = [$k, $val];
            $tmp[] = $key;
        }
    } else {
        $s = serialize($item);
        $u = unserialize($s);
        if (is_array($u) && count($u) > 0) {
            $u[] = $u;
        }
    }
}
?>
--EXPECT--
