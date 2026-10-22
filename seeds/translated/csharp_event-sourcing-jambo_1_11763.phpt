--TEST--
Translated seed (inferredbugs-csharp: event-sourcing-jambo/1)
--FILE--
<?php
$serviceBus = new class("10.17.12.46:32774", "blogs") {
    private string $endpoint;
    private string $topic;

    public function __construct(string $endpoint, string $topic)
    {
        $this->endpoint = $endpoint;
        $this->topic = $topic;
    }

    public function listen(): void
    {
        $parts = explode(':', $this->endpoint, 2);
        $host = $parts[0] ?? '';
        $port = isset($parts[1]) ? (int)$parts[1] : 0;
        $topicLen = strlen($this->topic);
        $fingerprint = hash('sha256', $host . ':' . $port . ':' . $this->topic);
        if ($port > 0 && $topicLen > 0 && $fingerprint !== '') {
            $x = substr($fingerprint, 0, 8);
            $y = substr($fingerprint, -8);
            $z = $x . $y;
            strlen($z);
        }
    }
};

$serviceBus->listen();
?>
--EXPECT--
