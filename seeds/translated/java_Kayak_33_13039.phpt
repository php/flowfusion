--TEST--
Translated seed (inferredbugs-java: Kayak/33)
--FILE--
<?php

class BusURL {
    private $host;
    private $bus;
    private $port;
    private $description;

    public function __construct($host, $port, $bus) {
        $this->host = $host;
        $this->port = $port;
        $this->bus = $bus;
    }

    public static function fromString($s) {
        if (preg_match('/^socket\:\/\/[a-zA-Z0-9]+\@[a-zA-Z0-9\-\.]+\:[0-9]+$/', $s)) {
            $b = new BusURL('', 0, '');
            $portSeparator = strrpos($s, ':');
            $at = strpos($s, '@');
            $b->port = (int)substr($s, $portSeparator + 1);
            $b->host = substr($s, $at + 1, $portSeparator - $at - 1);
            $b->bus = substr($s, 9, $at - 9);
            return $b;
        } else {
            return null;
        }
    }

    public function toString() {
        $s = $this->bus . '@' . $this->host . ':' . $this->port;
        if ($this->description) {
            $s .= ' (' . $this->description . ')';
        }
        return $s;
    }

    public function checkConnection() {
        $socket = @fsockopen($this->host, $this->port, $errNo, $errStr, 1);
        if ($socket) {
            fwrite($socket, "< hi >");
            $response = fread($socket, 6);
            fclose($socket);
            return $response === "< hi >";
        }
        return false;
    }
}

$b = BusURL::fromString('socket://bus@example.com:1234');
if ($b) {
    $b->checkConnection();
}
--EXPECT--
