--TEST--
Translated seed (inferredbugs-java: brave/9)
--FILE--
<?php

class Span {
    public $timestamp;
    public $startTick;
    
    public function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
        return $this;
    }

    public function getDuration() {
        if ($this->startTick !== null) {
            return max(1, microtime(true) * 1000 - $this->timestamp - ($this->startTick / 1000));
        }
        return $this->timestamp;
    }
}

class LocalTracer {
    private $currentLocalSpan;

    public function startNewSpan($component, $operation, $startTime = null) {
        $span = new Span();
        if ($startTime === null) {
            $this->currentLocalSpan = $span->setTimestamp(time() * 1000);
            $span->startTick = microtime(true) * 1000;
        } else {
            $this->currentLocalSpan = $span->setTimestamp($startTime);
            $span->startTick = null;
        }
        return $this->currentLocalSpan;
    }

    public function finishSpan($duration = null) {
        if ($duration === null) {
            $this->currentLocalSpan->startTick = null;
        }
        return $this->currentLocalSpan;
    }
}

$tracer = new LocalTracer();

$tracer->startNewSpan("componentname", "operationname");
$tracer->finishSpan();
$tracer->startNewSpan("componentname", "operationname", 1000);
$tracer->finishSpan();
$tracer->startNewSpan("componentname", "operationname");
$tracer->finishSpan(500);
$tracer->finishSpan();
--EXPECT--
