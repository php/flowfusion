--TEST--
Translated seed (inferredbugs-csharp: AlterNats/7)
--FILE--
<?php

class PublishCommand {
    private ?string $subject = null;
    private mixed $value = null;

    private function __construct() {
    }

    public static function create(string $subject, mixed $value): self {
        $instance = new self();
        $instance->subject = $subject;
        $instance->value = $value;
        return $instance;
    }

    public function write() {
        echo "Publishing to subject: {$this->subject} with value: " . json_encode($this->value) . "\n";
    }

    public function reset() {
        $this->subject = null;
        $this->value = null;
    }
}

class PublishBytesCommand {
    private ?string $stringSubject = null;
    private mixed $value;

    private function __construct() {
    }

    public static function create(string $subject, mixed $value): self {
        $instance = new self();
        $instance->stringSubject = $subject;
        $instance->value = $value;
        return $instance;
    }

    public function write() {
        echo "Publishing bytes to subject: {$this->stringSubject} with value: " . json_encode($this->value) . "\n";
    }

    public function reset() {
        $this->stringSubject = null;
        $this->value = null;
    }
}

function main() {
    $publishCommand = PublishCommand::create("test.subject", ["key" => "value"]);
    $publishCommand->write();

    $publishBytesCommand = PublishBytesCommand::create("test.bytes.subject", "byte data");
    $publishBytesCommand->write();

    $publishCommand->reset();
    $publishBytesCommand->reset();
}

main();
--EXPECT--
