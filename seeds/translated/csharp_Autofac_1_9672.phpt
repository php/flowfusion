--TEST--
Translated seed (inferredbugs-csharp: Autofac/1)
--FILE--
<?php

class ProfilingModule {
    private $profile;

    public function __construct() {
        $this->profile = new ContainerProfile();
    }

    public function load($moduleBuilder) {
        $moduleBuilder->registerInstance($this->profile);
    }

    public function attachToComponentRegistration($componentRegistry, $registration) {
        $standardRegistration = $registration instanceof ComponentRegistration ? $registration : null;
        if ($standardRegistration === null) {
            return;
        }

        $standardRegistration->setActivator(new ProfilingActivator(
            $registration,
            $standardRegistration->getActivator(),
            $this->profile
        ));
    }
}

class ContainerProfile {}

class ComponentRegistration {
    private $activator;

    public function setActivator($activator) {
        $this->activator = $activator;
    }

    public function getActivator() {
        return $this->activator;
    }
}

class ProfilingActivator {
    public function __construct($registration, $activator, $profile) {}
}

class ModuleBuilder {
    private $instances = [];

    public function registerInstance($instance) {
        $this->instances[] = $instance;
    }
}

function main() {
    $moduleBuilder = new ModuleBuilder();
    $profilingModule = new ProfilingModule();
    $profilingModule->load($moduleBuilder);

    $registration = new ComponentRegistration();
    $profilingModule->attachToComponentRegistration(null, $registration);
}

main();
--EXPECT--
