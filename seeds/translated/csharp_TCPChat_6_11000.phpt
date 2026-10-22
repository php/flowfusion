--TEST--
Translated seed (inferredbugs-csharp: TCPChat/6)
--FILE--
<?php
$queue = [];
$running = false;

$dispatch = function () use (&$queue, &$running, &$dispatch) {
    if ($running) return;
    $running = true;
    while ($queue) {
        [$cb, $state, $sync, &$done] = array_shift($queue);
        $cb($state);
        if ($sync) $done = true;
    }
    $running = false;
};

$post = function ($cb, $state) use (&$queue, &$dispatch) {
    $queue[] = [$cb, $state, false, null];
    $dispatch();
};

$send = function ($cb, $state) use (&$queue, &$dispatch) {
    $done = false;
    $queue[] = [$cb, $state, true, &$done];
    $dispatch();
    while (!$done) {
        $dispatch();
    }
};

$post(function ($s) use (&$post, &$send) {
    $post(function ($x) {}, $s + 1);
    $send(function ($x) {}, $s + 2);
}, 1);

$send(function ($s) use (&$post) {
    if ($s < 3) $post(function ($x) {}, $s);
}, 0);
?>
--EXPECT--
