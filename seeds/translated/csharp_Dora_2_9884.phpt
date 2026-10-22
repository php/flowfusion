--TEST--
Translated seed (inferredbugs-csharp: Dora/2)
--FILE--
<?php

interface ISystomClock {
    public function getCurrentTime($kind);
}

class SystomClock implements ISystomClock {
    public function getCurrentTime($kind) {
        return ($kind === 'Local') ? date('Y-m-d H:i:s') : gmdate('Y-m-d H:i:s');
    }
}

class ServiceProvider {
    private $services = [];
    
    public function addSingleton($abstract, $concrete) {
        $this->services[$abstract] = $concrete;
    }
    
    public function getRequiredService($abstract) {
        return $this->services[$abstract];
    }
}

$serviceProvider1 = new ServiceProvider();
$serviceProvider1->addSingleton(ISystomClock::class, new SystomClock());

for ($i = 0; $i < 5; $i++) {
    echo "Current time: " . $serviceProvider1->getRequiredService(ISystomClock::class)->getCurrentTime('Local') . "\n";
    sleep(1);
}

for ($i = 0; $i < 5; $i++) {
    echo "Current time: " . $serviceProvider1->getRequiredService(ISystomClock::class)->getCurrentTime('Utc') . "\n";
    sleep(1);
}

$serviceProvider2 = new ServiceProvider();
$serviceProvider2->addSingleton(ISystomClock::class, new SystomClock());

for ($i = 0; $i < 5; $i++) {
    echo "Current time: " . $serviceProvider2->getRequiredService(ISystomClock::class)->getCurrentTime('Local') . "\n";
    sleep(1);
}

for ($i = 0; $i < 5; $i++) {
    echo "Current time: " . $serviceProvider2->getRequiredService(ISystomClock::class)->getCurrentTime('Utc') . "\n";
    sleep(1);
}
--EXPECT--
