--TEST--
Translated seed (inferredbugs-csharp: P/12)
--FILE--
<?php
$buf = [];
$recv = [];
$max = 0;
$assume = false;
$status = 'Enabled';
$trigger = null;
$payload = null;
$queue = [];
for ($i = 0; $i < 3; $i++) {
    $queue[] = [$i, str_repeat("x", $i)];
}
$queue[] = [null, null];
foreach ($queue as $item) {
    [$e, $arg] = $item;
    if ($e === null) {
        continue;
    }
    $recv[(string)$e] = true;
    $buf[] = [$e, $arg];
    if ($max !== -1 && count($buf) > $max) {
        $status = 'Blocked';
        break;
    }
    if ($status === 'Blocked' && count($buf) > 0) {
        $status = 'Enabled';
    }
}
if ($status !== 'Halted') {
    while (!empty($buf)) {
        [$trigger, $payload] = array_shift($buf);
        if ($trigger === null) {
            $trigger = null;
            $payload = null;
        }
        if (isset($recv[(string)$trigger])) {
            $recv = [];
        } else {
            $status = 'Blocked';
            break;
        }
    }
}
echo "ok\n";
?>
--EXPECT--
