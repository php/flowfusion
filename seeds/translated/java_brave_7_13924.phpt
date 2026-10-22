--TEST--
Translated seed (inferredbugs-java: brave/7)
--FILE--
<?php

class Span {
    public $timestamp;
    public $startTick;
    public function setTimestamp($time) {
        $this->timestamp = $time;
        return $this;
    }
    public function getDuration() {
        return ($this->startTick !== null) ? (microtime(true) * 1000000 - $this->startTick) : null;
    }
}

class LocalTracer {
    private $currentLocalSpan;

    public function startNewSpan($component, $operation, $startTime = null) {
        $this->currentLocalSpan = (new Span())->setTimestamp($startTime ?? (microtime(true) * 1000000));
        if ($startTime === null) {
            $this->currentLocalSpan->startTick = microtime(true) * 1000000;
        }
        return $this->currentLocalSpan;
    }

    public function finishSpan($duration = null) {
        if ($duration !== null) {
            $this->currentLocalSpan->startTick = null; // ignore startTick
        }
        $this->currentLocalSpan->startTick = ($this->currentLocalSpan->startTick ?? microtime(true) * 1000000);
        $span = $this->currentLocalSpan;
        $this->currentLocalSpan = null;
        return $span;
    }

    public function getCurrentLocalSpan() {
        return $this->currentLocalSpan;
    }
}

$tracer = new LocalTracer();
$tracer->startNewSpan("componentname", "operationname");
$finished = $tracer->finishSpan();
echo $finished->getDuration() . PHP_EOL;

$tracer->startNewSpan("componentname", "operationname", 1000);
$finished = $tracer->finishSpan();
echo $finished->getDuration() . PHP_EOL;

$tracer->startNewSpan("componentname", "operationname");
usleep(500); // Sleep for 0.5 milliseconds
$finished = $tracer->finishSpan();
echo $finished->getDuration() . PHP_EOL;
--EXPECT--
