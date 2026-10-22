--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/1)
--FILE--
<?php
$listeners = new SplObjectStorage();

$efCoreListener = new class {
    public function __construct(private SplObjectStorage $listeners) {}
    public function start(): void
    {
        $this->listeners->attach(new class {
            public function __construct(private SplObjectStorage $listeners) {}
            public function __destruct()
            {
                $this->listeners->attach(new stdClass());
            }
        });
        $this->listeners->attach(new class {});
    }
};

$efCoreListener->start();

foreach ($listeners as $listener) {
    if ($listener instanceof stdClass) {
        continue;
    }
}
--EXPECT--
