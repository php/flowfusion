--TEST--
Translated seed (inferredbugs-csharp: TCPChat/2)
--FILE--
<?php
$queue = new SplQueue();
$running = true;
$worker = new Fiber(function () use (&$queue, &$running) {
    while ($running) {
        if ($queue->isEmpty()) {
            Fiber::suspend();
            continue;
        }
        $item = $queue->dequeue();
        ($item[0])($item[1]);
        $item[2] = true;
    }
});

$post = function ($cb, $state) use (&$queue, $worker) {
    $done = false;
    $queue->enqueue([$cb, $state, &$done]);
    if ($worker->isStarted()) {
        if ($worker->isSuspended()) {
            $worker->resume();
        }
    } else {
        $worker->start();
    }
};

$send = function ($cb, $state) use (&$queue, $worker) {
    $done = false;
    $queue->enqueue([$cb, $state, &$done]);
    if ($worker->isStarted()) {
        if ($worker->isSuspended()) {
            $worker->resume();
        }
    } else {
        $worker->start();
    }
    while (!$done) {
        if ($worker->isSuspended()) {
            $worker->resume();
        } else {
            break;
        }
    }
};

$post(function ($s) { $x = $s + 1; }, 1);
$send(function ($s) { $y = $s . ""; }, 2);
if ($worker->isSuspended()) {
    $worker->resume();
}
$running = false;
if ($worker->isSuspended()) {
    $worker->resume();
}
?>
--EXPECT--
