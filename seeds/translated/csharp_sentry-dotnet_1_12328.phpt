--TEST--
Translated seed (inferredbugs-csharp: sentry-dotnet/1)
--FILE--
<?php
declare(strict_types=1);

class SentryLoggingOptions {
    public $DiagnosticLogger = null;
    public $DiagnosticsLevel = 0;
    public $InitializeSdk = true;
    public $Dsn = null;
}

class LoggerFactory {
    public array $providers = [];
    public function createLogger(string $name): object { return (object)['name' => $name]; }
    public function addProvider(object $provider): void { $this->providers[] = $provider; }
}

class SentrySdk {
    public static bool $isEnabled = true;
    public static function useHub(object $hub): object { return $hub; }
}

class HubAdapter {
    public static ?object $instance = null;
}

class MelDiagnosticLogger {
    public function __construct(public object $logger, public int $level) {}
}

class OptionalHub {
    public function __construct(public SentryLoggingOptions $options) {}
}

class SentryLoggerProvider {
    public function __construct(public object $hub, public object $clock, public SentryLoggingOptions $options) {}
}

$factory = new LoggerFactory();
$options = new SentryLoggingOptions();
$options->InitializeSdk = true;
$options->Dsn = null;
$options->DiagnosticLogger = null;
$options->DiagnosticsLevel = 2;

if ($options->DiagnosticLogger === null) {
    $logger = $factory->createLogger('ISentryClient');
    $options->DiagnosticLogger = new MelDiagnosticLogger($logger, $options->DiagnosticsLevel);
}

if ($options->InitializeSdk) {
    if (SentrySdk::$isEnabled && $options->Dsn === null) {
        $hub = HubAdapter::$instance ??= (object)[];
    } else {
        $hub = new OptionalHub($options);
        SentrySdk::useHub($hub);
    }
} else {
    $hub = HubAdapter::$instance ??= (object)[];
}

$factory->addProvider(new SentryLoggerProvider($hub, (object)['clock' => true], $options));

echo count($factory->providers), PHP_EOL;
--EXPECT--
