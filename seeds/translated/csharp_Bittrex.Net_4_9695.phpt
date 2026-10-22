--TEST--
Translated seed (inferredbugs-csharp: Bittrex.Net/4)
--FILE--
<?php

class BittrexHubConnection {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
        // Simulating proxy usage which may cause an issue in connection
        $this->connection['proxy'] = 'http://localhost:8888';
    }

    public function getState() {
        return $this->connection['state'];
    }

    public function setEventHandler($event, $handler) {
        $this->connection[$event] = $handler;
    }

    public function createHubProxy($hubName) {
        return "HubProxy for " . $hubName;
    }

    public function start() {
        // Simulating connection start that could trigger a bug
        if ($this->connection['transport'] === 'websocket') {
            return "Starting connection with WebSocket.";
        }
        return "Unable to start connection, wrong transport.";
    }

    public function stop($timeout) {
        // Simulating stopping connection with a timeout
        return "Stopping connection with timeout of " . $timeout . " seconds.";
    }
}

// Simulating a connection state
$connection = [
    'state' => 'disconnected',
    'transport' => 'websocket',
];

$hubConnection = new BittrexHubConnection($connection);
$hubConnection->setEventHandler('onStateChange', function() {
    echo "State changed.\n";
});
echo $hubConnection->start() . "\n";
echo $hubConnection->stop(5) . "\n";
--EXPECT--
