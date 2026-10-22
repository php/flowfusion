--TEST--
Translated seed (inferredbugs-java: brave/8)
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

    public function finish($endTick) {
        $this->duration = max(1, $endTick - $this->startTick);
    }
}

class LocalTracer {
    private $currentSpan;

    public function startNewSpan($componentName, $operationName, $startTime = null) {
        $this->currentSpan = new Span();
        $this->currentSpan->setTimestamp($startTime ?? (microtime(true) * 1000));
        $this->currentSpan->startTick = (int)(microtime(true) * 1000000);
        return $this->currentSpan;
    }

    public function finishSpan() {
        if ($this->currentSpan) {
            $endTick = (int)(microtime(true) * 1000000);
            $this->currentSpan->finish($endTick);
            $this->currentSpan = null;
        }
    }

    public function getCurrentLocalSpan() {
        return $this->currentSpan;
    }
}

$tracer = new LocalTracer();
$span = $tracer->startNewSpan('componentname', 'operationname');
usleep(1000); // Simulate some processing time
$tracer->finishSpan();

$finishedSpan = $tracer->getCurrentLocalSpan();
echo $finishedSpan->getDuration(); // Outputs the duration of the span in microseconds
--EXPECT--
