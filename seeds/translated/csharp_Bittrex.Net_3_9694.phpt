--TEST--
Translated seed (inferredbugs-csharp: Bittrex.Net/3)
--FILE--
<?php

class HubConnection {
    public $state;
    public $stateChanged;
    public $closed;
    public $error;
    public $connectionSlow;
    public $cookieContainer;
    public $headers;

    public function __construct() {
        $this->state = 'Disconnected';
        $this->headers = [];
    }

    public function start() {
        // Simulate starting the connection
        $this->state = 'Connected';
        if (isset($this->stateChanged)) {
            call_user_func($this->stateChanged, $this->state);
        }
    }

    public function stop($timeout) {
        // Simulate stopping the connection after timeout
        sleep($timeout);
        $this->state = 'Disconnected';
        if (isset($this->closed)) {
            call_user_func($this->closed);
        }
    }
}

class BittrexHubConnection {
    private $connection;

    public function __construct(HubConnection $connection) {
        $this->connection = $connection;
    }

    public function start() {
        $this->connection->start();
    }

    public function stop($timeout) {
        $this->connection->stop($timeout);
    }

    public function setUserAgent($agent) {
        $this->connection->headers['User-Agent'] = $agent;
    }

    public function getUserAgent() {
        return $this->connection->headers['User-Agent'] ?? null;
    }
}

$hubConnection = new HubConnection();
$bittrexConnection = new BittrexHubConnection($hubConnection);

$bittrexConnection->setUserAgent('MyApp/1.0');
$bittrexConnection->start();
$bittrexConnection->stop(2);
--EXPECT--
