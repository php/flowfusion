--TEST--
Translated seed (inferredbugs-java: brave/2)
--FILE--
<?php

class ServerSpan {
    private $duration = 0;

    public function incThreadDuration($duration) {
        $this->duration += $duration;
    }
}

class ServerSpanThreadBinder {
    private $currentSpan;

    public function setCurrentSpan($span) {
        $this->currentSpan = $span;
    }

    public function getCurrentServerSpan() {
        return $this->currentSpan;
    }
}

class BraveRunnable {
    private $wrappedRunnable;
    private $serverSpanThreadBinder;
    private $currentServerSpan;

    public function __construct($runnable, $serverSpanThreadBinder) {
        $this->wrappedRunnable = $runnable;
        $this->serverSpanThreadBinder = $serverSpanThreadBinder;
        $this->currentServerSpan = $serverSpanThreadBinder->getCurrentServerSpan();
    }

    public function run() {
        $this->serverSpanThreadBinder->setCurrentSpan($this->currentServerSpan);
        $start = microtime(true);
        try {
            call_user_func($this->wrappedRunnable);
        } finally {
            $duration = (microtime(true) - $start) * 1000; // Convert to milliseconds
            $this->currentServerSpan->incThreadDuration($duration);
        }
    }
}

$serverSpan = new ServerSpan();
$serverSpanThreadBinder = new ServerSpanThreadBinder();
$serverSpanThreadBinder->setCurrentSpan($serverSpan);

$wrappedRunnable = function() {
    // Simulate some work here
    usleep(1000);
};

$braveRunnable = new BraveRunnable($wrappedRunnable, $serverSpanThreadBinder);
$braveRunnable->run();
--EXPECT--
