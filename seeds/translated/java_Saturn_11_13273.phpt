--TEST--
Translated seed (inferredbugs-java: Saturn/11)
--FILE--
<?php

class SaturnWorker {
    private $job;
    private $paused = false;
    private $triggered = false;
    private $halted = false;
    private $sigLock;

    public function __construct($job) {
        $this->job = $job;
        $this->sigLock = fopen("php://temp", "r+");
    }

    public function togglePause($pause) {
        flock($this->sigLock, LOCK_EX);
        $this->paused = $pause;
        fflush($this->sigLock);
        flock($this->sigLock, LOCK_UN);
    }

    public function halt() {
        flock($this->sigLock, LOCK_EX);
        $this->halted = true;
        fflush($this->sigLock);
        flock($this->sigLock, LOCK_UN);
    }

    public function trigger() {
        flock($this->sigLock, LOCK_EX);
        $this->triggered = true;
        fflush($this->sigLock);
        flock($this->sigLock, LOCK_UN);
    }

    public function run() {
        while (!$this->halted) {
            flock($this->sigLock, LOCK_SH);
            while ($this->paused && !$this->halted) {
                flock($this->sigLock, LOCK_UN);
                usleep(1000000);
                flock($this->sigLock, LOCK_SH);
            }
            if ($this->halted) {
                break;
            }
            $goAhead = !$this->halted && !$this->paused;
            if ($this->triggered) {
                $this->triggered = false;
            } else {
                $goAhead = $goAhead && !$this->paused;
            }
            flock($this->sigLock, LOCK_UN);
            if ($goAhead) {
                $this->job->execute();
            }
        }
    }
}

class Job {
    public function execute() {
        // Simulate job execution
        echo "Job executed\n";
    }
}

$job = new Job();
$worker = new SaturnWorker($job);
$worker->run();
--EXPECT--
