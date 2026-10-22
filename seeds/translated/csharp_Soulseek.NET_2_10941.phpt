--TEST--
Translated seed (inferredbugs-csharp: Soulseek.NET/2)
--FILE--
<?php
class SoulseekClientOptions {}

class SoulseekClient {
    public string $Address;
    public int $Port;
    public SoulseekClientOptions $Options;
    public string $DefaultAddress = '127.0.0.1';
    public int $DefaultPort = 2234;

    public function __construct() {
        $this->Address = $this->DefaultAddress;
        $this->Port = $this->DefaultPort;
        $this->Options = new SoulseekClientOptions();
    }
}

$s = new SoulseekClient();
$defaultServer = $s->DefaultAddress;
$defaultPort = $s->DefaultPort;
$defaultOptions = new SoulseekClientOptions();

if ($s->Address !== $defaultServer || $s->Port !== $defaultPort || get_class($s->Options) !== get_class($defaultOptions)) {
    throw new Exception('Unexpected defaults');
}

$s = null;
$s = new SoulseekClient();
if ($s === null) {
    throw new Exception('Instantiation failed');
}
?>
--EXPECT--
