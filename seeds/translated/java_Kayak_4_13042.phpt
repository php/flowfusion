--TEST--
Translated seed (inferredbugs-java: Kayak/4)
--FILE--
<?php

class BusURL {
    private $host;
    private $bus;
    private $port;
    private $hostName;
    private $description;
    private $timestamp;

    public function __construct($host, $port, $bus) {
        $this->host = $host;
        $this->port = $port;
        $this->bus = $bus;
    }

    public static function fromString($s) {
        if (preg_match('/^socket:\/\/[a-zA-Z0-9]+@[a-zA-Z0-9\-\.]+:[0-9]+$/', $s)) {
            $b = new BusURL('', 0, '');
            $portSeparator = strrpos($s, ':');
            $at = strpos($s, '@');
            $b->port = intval(substr($s, $portSeparator + 1));
            $b->host = substr($s, $at + 1, $portSeparator - $at - 1);
            $b->bus = substr($s, 9, $at - 9);
            return $b;
        }
        return null;
    }

    public function toString() {
        $s = $this->bus . '@' . ($this->hostName ?? $this->host . ':' . $this->port);
        if ($this->description !== null) {
            $s .= ' (' . $this->description . ')';
        }
        return $s;
    }
}

$url = BusURL::fromString('socket://myBus@localhost:8080');
echo $url->toString();
--EXPECT--
