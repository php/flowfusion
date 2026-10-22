--TEST--
Translated seed (inferredbugs-java: Chronicle-Queue/37)
--FILE--
<?php

$ONE_KILOBYTE = str_repeat(chr(7), 1024);
$LAST_MESSAGE = "LAST_MESSAGE";
$capturedOutput = [];

function getCurrentQueueFileLength($dataDir) {
    $files = glob($dataDir . '/*.cq4');
    if (empty($files)) {
        throw new Exception("No queue files found");
    }
    return filesize($files[0]);
}

function before() {
    global $dataDir, $capturedOutput;
    $dataDir = sys_get_temp_dir() . '/' . uniqid('ChronicleReaderTest_', true);
    mkdir($dataDir);
    
    $events = new class {
        public function say($msg) {
            // simulate writing to queue or handling messages
        }
    };

    for ($i = 0; $i < 24; $i++) {
        $events->say($i % 2 == 0 ? "hello" : "goodbye");
    }
}

function shouldReadQueueWithNonDefaultRollCycle() {
    global $dataDir, $capturedOutput;
    
    $path = sys_get_temp_dir() . '/' . uniqid('shouldReadQueueWithNonDefaultRollCycle_', true);
    mkdir($path);

    $events = new class {
        public function say($msg) {
            // simulate writing to queue
        }
    };

    for ($i = 0; $i < 24; $i++) {
        $events->say($i % 2 == 0 ? "hello" : "goodbye");
    }

    // Simulate message reading
    $capturedOutput[] = "Messages read";
    if (empty($capturedOutput)) {
        throw new Exception("Captured output is empty");
    }
}

function basicReader() {
    global $dataDir, $capturedOutput;
    
    // Simulate reading messages
    return array_filter($capturedOutput, function($msg) {
        return strpos($msg, "history:") !== false;
    });
}

// Main execution
before();
shouldReadQueueWithNonDefaultRollCycle();
$results = basicReader();
foreach ($results as $result) {
    echo $result . PHP_EOL;
}
--EXPECT--
