--TEST--
Translated seed (inferredbugs-csharp: Dora/1)
--FILE--
<?php

interface ISystomClock {
    public function getCurrentTime($dateTimeKind);
}

class SystomClock implements ISystomClock {
    public function getCurrentTime($dateTimeKind) {
        if ($dateTimeKind === 'Local') {
            return date('Y-m-d H:i:s');
        } else {
            return gmdate('Y-m-d H:i:s');
        }
    }
}

function simulateServiceProvider() {
    return new SystomClock();
}

$clock1 = simulateServiceProvider();

for ($i = 0; $i < 5; $i++) {
    echo "Current time: " . $clock1->getCurrentTime('Local') . "\n";
    sleep(1);
}

for ($i = 0; $i < 5; $i++) {
    echo "Current time: " . $clock1->getCurrentTime('Utc') . "\n";
    sleep(1);
}

$clock2 = simulateServiceProvider();

for ($i = 0; $i < 5; $i++) {
    echo "Current time: " . $clock2->getCurrentTime('Local') . "\n";
    sleep(1);
}

for ($i = 0; $i < 5; $i++) {
    echo "Current time: " . $clock2->getCurrentTime('Utc') . "\n";
    sleep(1);
}
--EXPECT--
