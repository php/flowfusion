--TEST--
Translated seed (inferredbugs-java: FastLogin/3)
--FILE--
<?php

class NameCheckTask {
    private $username;
    private $player;
    private $random;
    private $publicKey;

    public function __construct($username, $player, $publicKey) {
        $this->username = $username;
        $this->player = $player;
        $this->random = new Random();
        $this->publicKey = $publicKey;
    }

    public function run() {
        $this->onLogin($this->username, new ProtocolLibLoginSource($this->player, $this->random, $this->publicKey));
    }

    private function onLogin($username, $source) {
        // Simulating event call
        $event = new FastLoginPreLoginEvent($username, $source);
        // This would trigger the login process
    }
}

class Random {
    public function nextInt($bound) {
        return rand(0, $bound - 1);
    }
}

class ProtocolLibLoginSource {
    private $player;
    private $random;
    private $publicKey;

    public function __construct($player, $random, $publicKey) {
        $this->player = $player;
        $this->random = $random;
        $this->publicKey = $publicKey;
    }

    public function setOnlineMode() {
        // Simulating the online mode setting
    }
    
    public function getServerId() {
        return 'server_id_example';
    }

    public function getVerifyToken() {
        return random_bytes(16);
    }
}

class FastLoginPreLoginEvent {
    public function __construct($username, $source) {
        // Event initialization
    }
}

$player = "player_instance"; // Placeholder for player instance
$username = "exampleUser";
$publicKey = "public_key_placeholder"; // Placeholder for public key

$task = new NameCheckTask($username, $player, $publicKey);
$task->run();
--EXPECT--
