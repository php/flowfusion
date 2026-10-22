--TEST--
Translated seed (inferredbugs-csharp: dd-trace-dotnet/1)
--FILE--
<?php
class Tracer {
    public array $written = [];
    public string $DefaultServiceName = 'DefaultServiceName';
    public function Write($span): void { $this->written[] = $span; }
}

class Span {
    public array $tags = [];
    public ?string $OperationName = null;
    public float $start;
    public float $Duration = 0.0;
    private Tracer $tracer;
    private bool $finished = false;

    public function __construct(Tracer $tracer, $a = null, $b = null, $startTime = null) {
        $this->tracer = $tracer;
        $this->start = $startTime === null ? microtime(true) : (is_float($startTime) ? $startTime : $startTime);
    }

    public function SetTag($key, $value): void { $this->tags[$key] = (string)$value; }
    public function GetTag($key) { return $this->tags[$key] ?? null; }
    public function SetOperationName($name): void { $this->OperationName = $name; }

    public function Finish($endTime = null): void {
        if ($this->finished) return;
        $end = $endTime === null ? microtime(true) : (is_float($endTime) ? $endTime : $endTime);
        $this->Duration = max(0.0, $end - $this->start);
        $this->finished = true;
        $this->tracer->Write($this);
    }

    public function __destruct() { $this->Finish(); }
}

$tracer = new Tracer();

$span = new Span($tracer);
$span->SetTag("StringKey", "What's tracing");
$span->SetTag("IntKey", 42);
$span->SetTag("DoubleKey", 1.618);
$span->SetTag("BoolKey", true);
$span->SetOperationName("Op1");
$span->Finish();
$span->Finish();

$startTime = microtime(true) - 60.0;
$span = new Span($tracer, null, null, $startTime);
$span->Finish();

$span = new Span($tracer);
$span->Finish();

$startTime = microtime(true);
$endTime = $startTime + 0.01;
$span = new Span($tracer, null, null, $startTime);
$span->Finish($endTime);

$startTime = microtime(true);
$endTime = $startTime - 0.01;
$span = new Span($tracer, null, null, $startTime);
$span->Finish($endTime);

$span = new Span($tracer);
unset($span);

echo "ok\n";
?>
--EXPECT--
