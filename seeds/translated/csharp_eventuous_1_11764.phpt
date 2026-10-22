--TEST--
Translated seed (inferredbugs-csharp: eventuous/1)
--FILE--
<?php

class EventStoreClient {}
class NoOpCheckpointStore {}
interface IHostedService {}
interface IMessageSubscription {}
interface IEventHandler {}
class TestHandler implements IEventHandler {}
class TracedEventHandler {
    public $inner;
    function __construct($inner) { $this->inner = $inner; }
}
class MessageConsumer {
    public $handlers = [];
    function __construct($handlers) { $this->handlers = $handlers; }
}
class StreamSubscription implements IHostedService, IMessageSubscription {
    public $SubscriptionId;
    public $Consumer;
    public $EventStoreClient;
    public $CheckpointStore;
    function __construct($id, $client, $store, $handler) {
        $this->SubscriptionId = $id;
        $this->EventStoreClient = $client;
        $this->CheckpointStore = $store;
        $this->Consumer = new MessageConsumer([new TracedEventHandler($handler)]);
    }
}
class ServiceProvider {
    private $services = [];
    function __construct($services) { $this->services = $services; }
    function getService($type) { return $this->services[$type] ?? null; }
    function getServices($type) { return array_values(array_filter($this->services, fn($s) => $s instanceof $type)); }
}

$client = new EventStoreClient();
$store = new NoOpCheckpointStore();
$sub = new StreamSubscription("Test", $client, $store, new TestHandler());
$provider = new ServiceProvider([
    EventStoreClient::class => $client,
    NoOpCheckpointStore::class => $store,
    StreamSubscription::class => $sub,
]);

$resolved = $provider->getService(StreamSubscription::class);
$consumer = $resolved->Consumer;
$handlers = $consumer->handlers;
$innerHandler = $handlers[0]->inner;
$services = $provider->getServices(IHostedService::class);
$hostedSub = $services[0];

$resolved;
$consumer;
$handlers;
$innerHandler;
$hostedSub;
?>
--EXPECT--
