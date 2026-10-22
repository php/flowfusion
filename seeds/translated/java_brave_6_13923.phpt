--TEST--
Translated seed (inferredbugs-java: brave/6)
--FILE--
<?php

class Span {
    public $timestamp;
    public $startTick;
    private $duration;

    public function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
        return $this;
    }

    public function getDuration() {
        return $this->duration;
    }

    public function finish($duration) {
        $this->duration = $duration;
    }
}

class LocalTracer {
    private $currentSpan;

    public function startNewSpan($component, $operation, $startTime = null) {
        $this->currentSpan = (new Span())->setTimestamp($startTime ?? microtime(true) * 1000);
        if ($startTime === null) {
            $this->currentSpan->startTick = microtime(true) * 1000000;
        }
        return $this->currentSpan;
    }

    public function finishSpan($duration = null) {
        if ($duration === null) {
            $duration = (microtime(true) * 1000000) - $this->currentSpan->startTick;
        }
        $this->currentSpan->finish($duration);
        $this->currentSpan = null;
    }
}

$tracer = new LocalTracer();

$tracer->startNewSpan("componentname", "operationname");
usleep(500); // Simulate work
$tracer->finishSpan();

$tracer->startNewSpan("componentname", "operationname", 1000);
$tracer->finishSpan();
--EXPECT--
