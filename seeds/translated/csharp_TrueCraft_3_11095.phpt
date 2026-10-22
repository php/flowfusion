--TEST--
Translated seed (inferredbugs-csharp: TrueCraft/3)
--FILE--
<?php
class PacketReader {
    public function writePacket($stream, $packet) { }
    public function readPacket($stream) { return $stream->in->shift(); }
}
class Scheduler { public function update() { } }
class Queue {
    public $a = [];
    public function count() { return count($this->a); }
    public function tryDequeue(&$x) { if (!$this->a) return false; $x = array_shift($this->a); return true; }
    public function enqueue($x) { $this->a[] = $x; }
}
class Stream { public $in; function __construct() { $this->in = new SplQueue(); } }
class Client {
    public $loggedIn = false, $username = 'u', $packetQueue, $minecraftStream, $dataAvailable = false;
    function __construct() { $this->packetQueue = new Queue; $this->minecraftStream = new Stream; }
}
class DisconnectPacket { public $ID = 1; }
class PingPacket { public $ID = 2; }
$packetReader = new PacketReader();
$scheduler = new Scheduler();
$clients = [new Client()];
$handlers = array_fill(0, 256, null);
$handlers[2] = function($packet, $client, $server) { $client->dataAvailable = false; };
$clients[0]->packetQueue->enqueue(new DisconnectPacket());
$clients[0]->packetQueue->enqueue(new PingPacket());
$clients[0]->dataAvailable = true;
for ($i = 0; $i < count($clients); $i++) {
    $client = $clients[$i];
    if ($client->packetQueue->count() != 0) {
        while (!$client->packetQueue->tryDequeue($packet)) { }
        $packetReader->writePacket($client->minecraftStream, $packet);
        if ($packet instanceof DisconnectPacket) {
            array_splice($clients, $i, 1);
            $i--;
            continue;
        }
    }
    if ($client->dataAvailable) {
        $packet = $packetReader->readPacket($client->minecraftStream);
        if ($handlers[$packet->ID] !== null) {
            try {
                $handlers[$packet->ID]($packet, $client, null);
            } catch (Throwable $e) {
                array_splice($clients, $i, 1);
                $i--;
            }
        }
    }
}
?>
--EXPECT--
