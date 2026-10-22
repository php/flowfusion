--TEST--
Translated seed (inferredbugs-java: HikariCP/4)
--FILE--
<?php

$threads = isset($argv[2]) ? (int)$argv[2] : 100;
$poolMax = isset($argv[3]) ? (int)$argv[3] : 100;

if ($argc < 4) {
    echo "Usage: <poolname> <threads> <poolsize>\n";
    exit(0);
}

class MixedRunner implements Runnable {
    private $iter;
    private $counter = 0;
    private $start;
    private $finish;

    public function __construct($iter) {
        $this->iter = $iter;
    }

    public function run() {
        $this->start = microtime(true);
        for ($i = 0; $i < $this->iter; $i++) {
            for ($j = 0; $j < 100; $j++) {
                for ($k = 0; $k < 100; $k++) {
                    $this->counter += ($i + $j + $k); // Interaction triggering JIT
                }
            }
        }
        $this->finish = microtime(true);
    }

    public function getElapsed() {
        return ($this->finish - $this->start) * 1000; // return in milliseconds
    }

    public function getCounter() {
        return $this->counter;
    }
}

class Benchmarker {
    public static function runBenchmarks($threads, $iter) {
        $runners = [];
        for ($i = 0; $i < $threads; $i++) {
            $runners[] = new MixedRunner($iter);
        }

        $start = microtime(true);
        foreach ($runners as $runner) {
            $runner->run();
        }
        $finish = microtime(true);

        $max = 0;
        $total = 0;
        foreach ($runners as $runner) {
            $elapsed = $runner->getElapsed();
            $total += $elapsed;
            if ($elapsed > $max) {
                $max = $elapsed;
            }
        }
        $avg = $total / $threads;

        echo sprintf("max=%.2f, avg=%.2f\n", $max, $avg);
        return $finish - $start;
    }
}

echo "Benchmarking with $threads threads and pool size $poolMax\n";
Benchmarker::runBenchmarks($threads, 1000);
--EXPECT--
