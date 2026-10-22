--TEST--
Translated seed (inferredbugs-java: brave/5)
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
    private $currentLocalSpan;

    public static function builder() {
        return new self();
    }

    public function startNewSpan($component, $operation) {
        $this->currentLocalSpan = (new Span())->setTimestamp((int)(microtime(true) * 1000));
        $this->currentLocalSpan->startTick = (int)(microtime(true) * 1000000);
        return $this->currentLocalSpan;
    }

    public function finishSpan($duration = null) {
        if ($duration === null && $this->currentLocalSpan) {
            $endTick = (int)(microtime(true) * 1000000);
            $this->currentLocalSpan->finish($endTick - $this->currentLocalSpan->startTick);
        } else {
            $this->currentLocalSpan->finish($duration);
        }
    }

    public function getCurrentLocalSpan() {
        return $this->currentLocalSpan;
    }
}

$tracer = LocalTracer::builder();
$span = $tracer->startNewSpan("componentname", "operationname");

usleep(500000); // Simulate work

$tracer->finishSpan();
$finished = $tracer->getCurrentLocalSpan();

echo $finished->getDuration();
--EXPECT--
