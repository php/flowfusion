--TEST--
Translated seed (inferredbugs-csharp: Stove/1)
--FILE--
<?php
class Args implements ArrayAccess {
    private array $d;
    function __construct(array $d) { $this->d = $d; }
    function offsetExists($o): bool { return array_key_exists($o, $this->d); }
    function offsetGet($o): mixed { return $this->d[$o]; }
    function offsetSet($o, $v): void { $this->d[$o] = $v; }
    function offsetUnset($o): void { unset($this->d[$o]); }
}

class Session {
    public $Connection;
    public $Transaction;
    function __construct($c, $t) { $this->Connection = $c; $this->Transaction = $t; }
}

class AdoTransaction {
    private $trans;
    function __construct($t) { $this->trans = $t; }
}

class SessionProvider {
    function GetSession(string $type): Session {
        $conn = "conn:" . $type;
        $tx = new AdoTransaction("tx:" . $type);
        return new Session($conn, $tx);
    }
}

$sessionContextType = "EdgeCaseType";
$provider = new SessionProvider();
$method = new ReflectionMethod($provider, "GetSession");
$session = $method->invoke($provider, $sessionContextType);
$rt = new ReflectionObject($session->Transaction);
$prop = $rt->getProperty("trans");
$prop->setAccessible(true);
$dbTransaction = $prop->getValue($session->Transaction);
$dbConnection = $session->Connection;

$args = new Args(["SessionContextType" => $sessionContextType]);
if ($dbTransaction !== "tx:" . $args["SessionContextType"]) {
    throw new RuntimeException("transaction mismatch");
}
if ($dbConnection !== "conn:" . $args["SessionContextType"]) {
    throw new RuntimeException("connection mismatch");
}
?>
--EXPECT--
