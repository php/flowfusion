--TEST--
Translated seed (inferredbugs-java: delight-nashorn-sandbox/1)
--FILE--
<?php

class CPUAbuseException extends Exception {}

class Sandbox {
    protected $maxCPUTimeInMs = 0;
    protected $debug = false;

    public function setMaxCPUTime($limit) {
        $this->maxCPUTimeInMs = $limit;
        return $this;
    }

    public function eval($code) {
        if ($this->maxCPUTimeInMs == 0) {
            eval($code);
            return;
        }
        
        // Simulated CPU time check
        $startTime = microtime(true);
        $interruptToken = rand(0, 1000000);
        
        // Embedding interruption logic
        $code = str_replace(";", ";intCheckForInterruption$interruptToken();", $code);
        $code .= "\nfunction intCheckForInterruption$interruptToken() { if (microtime(true) - $startTime > " . ($this->maxCPUTimeInMs / 1000) . ") { throw new CPUAbuseException('CPU time exceeded'); } }";

        try {
            eval($code);
        } catch (CPUAbuseException $e) {
            echo $e->getMessage();
        }
    }
}

$sandbox = new Sandbox();
$sandbox->setMaxCPUTime(100);
$sandbox->eval('for ($i=0; $i < 10000000; $i++) { }');
--EXPECT--
