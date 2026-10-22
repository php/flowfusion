--TEST--
Translated seed (inferredbugs-java: Chronicle-Queue/22)
--FILE--
<?php

class Chronicle {
    private $data = [];
    private $index = 0;

    public function append($value) {
        $this->data[] = $value;
    }

    public function nextIndex() {
        return $this->index < count($this->data);
    }

    public function parseLong() {
        return $this->data[$this->index++];
    }

    public function finish() {
        // In a real implementation, this could do cleanup or finalization
    }

    public function clear() {
        $this->data = [];
        $this->index = 0;
    }

    public function close() {
        // Close resources if necessary
    }
}

function runChronicleTest() {
    $source = new Chronicle();
    $sink = new Chronicle();
    $runs = 100;

    $appenderThread = function() use ($source, $runs) {
        for ($i = 0; $i < $runs; $i++) {
            $value = 1000000000 + $i;
            $source->append($value);
            usleep(10000);
        }
    };

    $tailerThread = function() use ($sink, $runs) {
        for ($i = 0; $i < $runs; $i++) {
            while (!$sink->nextIndex()) {
                usleep(1000);
            }
            $value = 1000000000 + $i;
            $val = $sink->parseLong();
            if ($value !== $val) {
                echo "Mismatch: expected $value, got $val\n";
            }
            $sink->finish();
        }
    };

    $appender = new Thread($appenderThread);
    $tailer = new Thread($tailerThread);

    $appender->start();
    $tailer->start();

    $appender->join();
    $tailer->join();

    $source->close();
    $sink->close();
}

runChronicleTest();
--EXPECT--
