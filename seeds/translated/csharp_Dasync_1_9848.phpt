--TEST--
Translated seed (inferredbugs-csharp: Dasync/1)
--FILE--
<?php

class DelayPromise
{
    private $timer;

    public function __construct()
    {
        $this->timer = new Timer();
    }

    public function getTimer()
    {
        return $this->timer;
    }
}

class Timer
{
    private $start;
    private $initialDelay;
    
    public function __construct()
    {
        $this->start = time();
        $this->initialDelay = rand(1, 5);
    }

    public function getSettings()
    {
        return [$this->start, $this->initialDelay];
    }

    public function dispose()
    {
        // Simulate timer disposal.
    }
}

function isDelayPromise($task)
{
    return get_class($task) === "DelayPromise";
}

function tryGetTimer($task, &$timer)
{
    $timer = null;
    if (isDelayPromise($task)) {
        $timer = $task->getTimer();
    }
    return $timer !== null;
}

function tryGetTimerStartAndDelay($delayPromise, &$startTime, &$delay)
{
    if (tryGetTimer($delayPromise, $timer)) {
        list($start, $initialDelay) = $timer->getSettings();

        if ($start !== null && $initialDelay !== null) {
            $startTime = $start;
            $delay = $initialDelay;
            return true;
        }
    }

    $startTime = null;
    $delay = null;
    return false;
}

function tryCancelTimer($delayPromise)
{
    if (tryGetTimer($delayPromise, $timer)) {
        $timer->dispose();
        return true;
    }
    return false;
}

$delayPromise = new DelayPromise();
$startTime = null;
$delay = null;

tryGetTimerStartAndDelay($delayPromise, $startTime, $delay);
tryCancelTimer($delayPromise);
--EXPECT--
