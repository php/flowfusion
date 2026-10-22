--TEST--
Translated seed (inferredbugs-csharp: TCPChat/3)
--FILE--
<?php
$q = new SplQueue();
$running = true;

$worker = new Fiber(function () use (&$q, &$running) {
    while ($running) {
        if ($q->isEmpty()) {
            Fiber::suspend();
            continue;
        }
        $e = $q->dequeue();
        ($e[0])($e[1]);
        if ($e[2] !== null) {
            $e[2]->set();
        }
    }
});

class ManualResetEvent {
    private bool $set = false;
    public function set(): void { $this->set = true; }
    public function wait(): void { while (!$this->set) { Fiber::suspend(); } }
}

$worker->start();

$post = function (callable $d, mixed $state) use (&$q, $worker) {
    $q->enqueue([$d, $state, null]);
    if ($worker->isSuspended()) $worker->resume();
};

$send = function (callable $d, mixed $state) use (&$q, $worker) {
    $e = new ManualResetEvent();
    $q->enqueue([$d, $state, $e]);
    if ($worker->isSuspended()) $worker->resume();
    $e->wait();
};

$post(function ($s) { $GLOBALS['x'] = $s + 1; }, 41);
$send(function ($s) { $GLOBALS['y'] = $s * 2; }, 21);

for ($i = 0; $i < 2; $i++) {
    if ($worker->isSuspended()) $worker->resume();
}

$running = false;
if ($worker->isSuspended()) $worker->resume();
?>
--EXPECT--
