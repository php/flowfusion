--TEST--
Translated seed (inferredbugs-csharp: Cirqus/16)
--FILE--
<?php

class ViewManagerEventDispatcherConfiguationBuilder {
    private $registrar;
    private $instances = [];

    public function __construct($registrar) {
        $this->registrar = $registrar;
    }

    public function WithWaitHandle($handle) {
        $this->RegisterInstance($handle);
        return $this;
    }

    public function WithMaxDomainEventsPerBatch($max) {
        $this->RegisterInstance($max);
        return $this;
    }

    public function WithProfiler($profiler) {
        $this->RegisterInstance($profiler);
        return $this;
    }

    public function AutomaticallyRedistributeViews($id, $autoDistributionState) {
        $this->RegisterInstance(new AutoDistributionViewManagerEventDispatcher($id, $autoDistributionState));
        return $this;
    }

    private function RegisterInstance($instance) {
        $this->instances[] = $instance;
    }
}

class AutoDistributionViewManagerEventDispatcher {
    private $id;
    private $autoDistributionState;

    public function __construct($id, $autoDistributionState) {
        $this->id = $id;
        $this->autoDistributionState = $autoDistributionState;
    }
}

$registrar = new stdClass(); // Placeholder for Registrar
$autoDistributionState = new stdClass(); // Placeholder for AutoDistributionState

$builder = new ViewManagerEventDispatcherConfiguationBuilder($registrar);
$builder->WithWaitHandle(new stdClass())
        ->WithMaxDomainEventsPerBatch(100)
        ->WithProfiler(new stdClass())
        ->AutomaticallyRedistributeViews('view_id', $autoDistributionState);
--EXPECT--
